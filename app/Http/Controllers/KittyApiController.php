<?php


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KittyApiController
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        $order = 'desc';

        if(request()->has('order')) {
            $order = request('order');
        }

        $url = config('kitties-api.endpoints.kitties_list') . $order;
        $kittyListData = $this->getApiRequests($url);

        if($kittyListData['status'] === 'error') {
            $errorMsg = $kittyListData['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $kittyRecords = collect($kittyListData['data']);
        $page = request()->has('page') ? request('page') : 1;
        $kitties = $this->createPaginator($kittyRecords, count($kittyRecords), 8, $page);
        $data = compact('kitties');

        return view('kitties.index', $data);
    }

    public function breeds()
    {
        $breeds = $this->getApiRequests(config('kitties-api.endpoints.kitty_breeds'));

        if($breeds['status'] === 'error') {
            $errorMsg = $breeds['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $breed = 'beng';
        $breedImage = 'beng';

        if(request()->has('breed')) {
            $breed = request('breed');
            $breedImage = request('breed');
        }

        $breedUrl = config('kitties-api.endpoints.kitty_breed_search') . $breed;
        $selectedBreed = $this->getApiRequests($breedUrl);

        if($selectedBreed['status'] === 'error') {
            $errorMsg = $selectedBreed['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $breedImageUrl = config('kitties-api.endpoints.kitty_breed_images') . $breedImage;
        $breedImages = $this->getApiRequests($breedImageUrl);

        if($breedImages['status'] === 'error') {
            $errorMsg = $breedImages['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $kittyBreeds = $breeds['data'];
        $kittyBreedInfo = null;

        if(count($selectedBreed['data']) > 0) {
            $kittyBreedInfo = $selectedBreed['data'][0];
        }

        $kittyImages = $breedImages['data'];
        $data = compact('kittyBreeds', 'kittyBreedInfo', 'kittyImages');

        return view('kitties.breeds', $data);
    }

    public function userImages()
    {
        $userId = config('kitties-api.user_id');
        $images = $this->getApiRequests(config('kitties-api.endpoints.kitty_user_images'));

        if($images['status'] === 'error') {
            $errorMsg = $images['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $userImages = null;

        if(count($images['data']) > 0) {
            $userImageRecords = collect($images['data']);
            $page = request()->has('page') ? request('page') : 1;
            $userImages = $this->createPaginator($userImageRecords, count($userImageRecords), 4, $page);
        }

        $data = compact('userId', 'userImages');

        return view('kitties.user-images', $data);
    }

    public function favourites()
    {
        $url = config('kitties-api.endpoints.kitty_image') . 'search?order=rand&limit=4';
        $images = $this->getApiRequests($url);

        if($images['status'] === 'error') {
            $errorMsg = $images['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $kittyImages = $images['data'];

        $favouritesUrl = config('kitties-api.endpoints.kitty_favourite_images');
        $favouriteImages = $this->getApiRequests($favouritesUrl);

        if($favouriteImages['status'] === 'error') {
            $errorMsg = $favouriteImages['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $favourites = null;

        if(count($favouriteImages['data']) > 0) {
            $favouriteImageRecords = collect($favouriteImages['data']);
            $page = request()->has('page') ? request('page') : 1;
            $favourites = $this->createPaginator($favouriteImageRecords, count($favouriteImageRecords), 8, $page);
        }

        $data = compact('kittyImages', 'favourites');

        return view('kitties.user-favourites', $data);
    }

    public function votes()
    {
        $url = config('kitties-api.endpoints.kitty_image') . 'search?order=rand&limit=4';
        $images = $this->getApiRequests($url);

        if($images['status'] === 'error') {
            $errorMsg = $images['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $kittyImages = $images['data'];

        $votedImages = $this->getApiRequests(config('kitties-api.endpoints.kitty_user_votes'));

        if($votedImages['status'] === 'error') {
            $errorMsg = $votedImages['message'];
            $data = compact('errorMsg');

            return view('error', $data);
        }

        $votes = null;

        if(count($votedImages['data']) > 0) {
            $favouriteImageRecords = collect($votedImages['data']);
            $page = request()->has('page') ? request('page') : 1;
            $votes = $this->createPaginator($favouriteImageRecords, count($favouriteImageRecords), 8, $page);
        }

        $data = compact('kittyImages', 'votes');

        return view('kitties.votes', $data);
    }

    public function upload(Request $request): JsonResponse
    {
        $file = $request->file;
        $image_path = $file->getPathname();

        try {
            $response = $this->client->post(config('kitties-api.endpoints.kitty_image_upload'),
                [
                    'multipart' => [
                        [
                            'name' => 'file',
                            'contents' => fopen($image_path, 'r'),
                            'filename' => $file->getClientOriginalName()
                        ],
                        [
                            'name' => 'sub_id',
                            'contents' => config('kitties-api.user_id')
                        ]
                    ],
                    'headers' => [
                        'x-api-key' => config('kitties-api.auth.api_key'),
                    ]
                ],
            );
        } catch(RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $errorMsg = $data->message;

                return response()->json(['status' => 'error', 'message' => $errorMsg]);
            }
        }

        $dataUpload = json_decode($response->getBody()->getContents());
        $analysisUrl = config('kitties-api.endpoints.kitty_image') . $dataUpload->id . '/analysis';
        $analysis = $this->getApiRequests($analysisUrl);

        if($analysis['status'] === 'error') {
            $errorMsg = $analysis['message'];

            return response()->json(['status' => 'error', 'message' => $errorMsg]);
        }

        return response()->json(['status' => $analysis['status'], 'message' => 'Image was successfully uploaded and approved.']);
    }

    public function addFavourite($id): JsonResponse
    {
        try {
            $this->client->post(config('kitties-api.endpoints.kitty_favourites'), [
                'headers' => [
                    'x-api-key' => config('kitties-api.auth.api_key')
                ],
                'json' => [
                    'image_id' => $id,
                    'sub_id' => config('kitties-api.user_id')
                ]
            ]);
        } catch (RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $errorMsg = $data->message;

                return response()->json(['status' => 'error', 'message' => $errorMsg]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Image was successfully added to your favourites']);
    }

    public function addVote($id, Request $request): JsonResponse
    {
        try {
            $this->client->post(config('kitties-api.endpoints.kitty_image_vote'), [
                'headers' => [
                    'x-api-key' => config('kitties-api.auth.api_key')
                ],
                'json' => [
                    'image_id' => $id,
                    'sub_id' => config('kitties-api.user_id'),
                    'value' => $request->vote
                ]
            ]);
        } catch (RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $errorMsg = $data->message;

                return response()->json(['status' => 'error', 'message' => $errorMsg]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Image was successfully added to your votes']);
    }

    public function delete($id, Request $request): JsonResponse
    {
        $url = config('kitties-api.endpoints.kitty_image') . $id;

        if($request->type === 'favourite') {
            $url = config('kitties-api.endpoints.kitty_favourites') . $id;
        }

        if($request->type === 'vote') {
            $url = config('kitties-api.endpoints.kitty_image_vote') . $id;
        }

        try {
            $this->client->delete($url,
                ['headers' => ['x-api-key' => config('kitties-api.auth.api_key')]]);
        } catch (RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $errorMsg = $data->message;

                return response()->json(['status' => 'error', 'message' => $errorMsg]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Image was deleted successfully']);
    }

    private function getApiRequests(string $url): array
    {
        try {
            $response = $this->client->get($url,
                ['headers' => ['x-api-key' => config('kitties-api.auth.api_key')]]);
        } catch(RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $errorMsg = $data->message;

                return ['status' => 'error', 'message' => $errorMsg];
            }
        }

        return ['status' => 'success', 'data' => json_decode($response->getBody()->getContents())];
    }

    private function createPaginator(Collection $collection, $total, int $perPage, int $page): LengthAwarePaginator
    {
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            $collection->slice($offset, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
