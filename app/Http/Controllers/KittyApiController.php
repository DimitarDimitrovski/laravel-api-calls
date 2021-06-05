<?php


namespace App\Http\Controllers;

use App\Http\Modules\API\CallerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KittyApiController
{
    /**
     * @var CallerInterface
     */
    private $caller;

    public function __construct(CallerInterface $caller)
    {
        $this->caller = $caller;
    }

    public function index()
    {
        $kitties = $this->caller->getApiRequest($this->caller->getEndPoint('all'), request('order', config('kitties-api.default_order')));

        if($kitties['status'] === 'success') {
            $page = request()->has('page') ? request('page') : 1;
            $kitties['data'] = $this->createPaginator(collect($kitties['data']), count($kitties['data']), 8, $page);
        }

        $data = compact('kitties');

        return view('kitties.index', $data);
    }

    public function breeds()
    {
        $breeds = $this->caller->getApiRequest($this->caller->getEndPoint('breeds'));
        $breedSelected = request('breed', config('kitties-api.default_breed'));
        $breedImages = $this->caller->getApiRequest($this->caller->getEndPoint('breed-images'), $breedSelected);
        $breedInfo = $this->caller->getApiRequest($this->caller->getEndPoint('breed-search'), $breedSelected);

        if($breedInfo['status'] === 'success' && !empty($breedInfo['data'])) {
            $breedInfo['data'] = $breedInfo['data'][0];
        }

        $data = compact('breeds', 'breedInfo', 'breedImages');

        return view('kitties.breeds', $data);
    }

    public function userImages()
    {
        $images = $this->caller->getApiRequest($this->caller->getEndPoint('user-images'), config('kitties-api.user_id'));

        if($images['status'] === 'success' && count($images['data']) > 0) {
            $page = request()->has('page') ? request('page') : 1;
            $images['data'] = $this->createPaginator(collect($images['data']), count($images['data']), 4, $page);
        }

        $data = compact('images');

        return view('kitties.user-images', $data);
    }

    public function favourites()
    {
        $images = $this->caller->getApiRequest($this->caller->getEndPoint('random'));
        $favouriteImages = $this->caller->getApiRequest($this->caller->getEndPoint('favourites'));

        if($favouriteImages['status'] === 'success' && count($favouriteImages['data']) > 0) {
            $page = request()->has('page') ? request('page') : 1;
            $favouriteImages['data'] = $this->createPaginator(collect($favouriteImages['data']), count($favouriteImages['data']), 8, $page);
        }

        $data = compact('images', 'favouriteImages');

        return view('kitties.user-favourites', $data);
    }

    public function votes()
    {
        $images = $this->caller->getApiRequest($this->caller->getEndPoint('random'));
        $votedImages = $this->caller->getApiRequest($this->caller->getEndPoint('user-votes'));

        if($votedImages['status'] === 'success' && count($votedImages['data']) > 0) {
            $page = request()->has('page') ? request('page') : 1;
            $votedImages['data'] = $this->createPaginator(collect($votedImages['data']), count($votedImages['data']), 8, $page);
        }

        $data = compact('images', 'votedImages');

        return view('kitties.votes', $data);
    }

    public function upload(Request $request): JsonResponse
    {
        $file = $request->file;
        $image_path = $file->getPathname();
        $body = [
            [
                'name' => 'file',
                'contents' => fopen($image_path, 'r'),
                'filename' => $file->getClientOriginalName()
            ],
            [
                'name' => 'sub_id',
                'contents' => config('kitties-api.user_id')
            ]
        ];

        $postData = $this->caller->post($this->caller->getEndPoint('image-upload'), 'multipart', $body);

        if($postData['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $postData['message']]);
        }

        $analysisUrl = config('kitties-api.endpoints.kitty_image') . $postData['data']->id . '/analysis';
        $analysis = $this->caller->getApiRequest($analysisUrl);

        if($analysis['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $analysis['message']]);
        }

        return response()->json(['status' => $analysis['status'], 'message' => 'Image was successfully uploaded and approved.']);
    }

    public function addFavourite($id): JsonResponse
    {
        $body = [
            'image_id' => $id,
            'sub_id' => config('kitties-api.user_id')
        ];

        $postResponse = $this->caller->post($this->caller->getEndPoint('favourites-action'), 'json', $body);

        if($postResponse['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $postResponse['message']]);
        }

        return response()->json(['status' => 'success', 'message' => 'Image was successfully added to your favourites']);
    }

    public function addVote($id, Request $request): JsonResponse
    {
        $body = [
            'image_id' => $id,
            'sub_id' => config('kitties-api.user_id'),
            'value' => $request->vote
        ];

        $postResponse = $this->caller->post($this->caller->getEndPoint('vote-actions'), 'json', $body);

        if($postResponse['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $postResponse['message']]);
        }

        return response()->json(['status' => 'success', 'message' => 'Image was successfully added to your votes']);
    }

    public function delete($id, Request $request): JsonResponse
    {
        $url = $this->caller->getEndPoint('delete-image', $id);

        if($request->type === 'favourite') {
            $url = $this->caller->getEndPoint('favourites-action', $id);
        }

        if($request->type === 'vote') {
            $url = $this->caller->getEndPoint('vote-actions', $id);
        }

        $response = $this->caller->delete($url);

        if($response['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => $response['message']]);
        }

        return response()->json(['status' => 'success', 'message' => 'Image was deleted successfully']);
    }

    private function createPaginator(Collection $collection, int $total, int $perPage, int $page): LengthAwarePaginator
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
