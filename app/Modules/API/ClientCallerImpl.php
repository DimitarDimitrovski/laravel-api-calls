<?php


namespace App\Modules\API;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class ClientCallerImpl implements CallerInterface
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $action
     * @param string $param
     * @return string
     */
    public function getEndPoint(string $action, string $param = ''): string
    {
        switch($action) {
            case 'breeds': {
                return config('kitties-api.endpoints.kitty_breeds').$param;
            }
            case 'breed-search': {
                return config('kitties-api.endpoints.kitty_breed_search').$param;
            }
            case 'breed-images': {
                return config('kitties-api.endpoints.kitty_breed_images').$param;
            }
            case 'user-images': {
                return config('kitties-api.endpoints.kitty_user_images').$param;
            }
            case 'image-upload': {
                return config('kitties-api.endpoints.kitty_image_upload').$param;
            }
            case 'delete-image': {
                return config('kitties-api.endpoints.kitty_image').$param;
            }
            case 'random': {
                return config('kitties-api.endpoints.kitty_images_random').$param;
            }
            case 'favourites': {
                return config('kitties-api.endpoints.kitty_favourite_images').$param;
            }
            case 'favourites-action': {
                return config('kitties-api.endpoints.kitty_favourites').$param;
            }
            case 'user-votes': {
                return config('kitties-api.endpoints.kitty_user_votes').$param;
            }
            case 'vote-actions': {
                return config('kitties-api.endpoints.kitty_image_vote').$param;
            }
            default: {
                return config('kitties-api.endpoints.kitties_list').$param;
            }
        }
    }

    public function getApiRequest(string $url, string $param = ''): array
    {
        $imageData = ['data' => null, 'status' => 'error', 'message' => ''];

        try {
            $response = $this->client->get($url.$param,
                ['headers' => ['x-api-key' => config('kitties-api.auth.api_key')]]);
            $imageData['data'] = json_decode($response->getBody()->getContents());
            $imageData['status'] = 'success';
        }
        catch (ConnectException $exception) {
            $imageData['message'] = $exception->getHandlerContext()['error'];
        }
        catch(RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $imageData['message'] = $data->message;;
            }
        }

        return $imageData;
    }

    public function post(string $url, string $type, array $body): array
    {
        $postData = ['data' => null, 'status' => 'error', 'message' => ''];

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'x-api-key' => config('kitties-api.auth.api_key')
                ],
                $type => $body
            ]);
            $postData['data'] = json_decode($response->getBody()->getContents());
            $postData['status'] = 'success';

        }
        catch (ConnectException $exception) {
            $postData['message'] = $exception->getHandlerContext()['error'];
        }
        catch (RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $postData['message'] = $data->message;
            }
        }

        return $postData;
    }

    public function delete(string $url): array
    {
        $deleteData = ['status' => 'error', 'message' => ''];

        try {
            $this->client->delete($url,
                ['headers' => ['x-api-key' => config('kitties-api.auth.api_key')]]);
            $deleteData['status'] = 'success';
        }
        catch (ConnectException $exception) {
            $deleteData['message'] = $exception->getHandlerContext()['error'];
        }
        catch (RequestException $exception) {
            if($exception->hasResponse()) {
                $data = json_decode($exception->getResponse()->getBody()->getContents());
                $deleteData['message'] = $data->message;
            }
        }

        return $deleteData;
    }
}
