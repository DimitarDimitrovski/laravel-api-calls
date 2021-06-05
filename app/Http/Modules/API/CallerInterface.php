<?php


namespace App\Http\Modules\API;


interface CallerInterface
{
    /**
     * @param string $action
     * @param string $param
     * @return string
     */
    public function getEndPoint(string $action, string $param): string;

    /**
     * @param string $url
     * @param string $param
     * @return mixed
     */
    public function getApiRequest(string $url, string $param);

    public function post(string $url, string $type, array $body): array;

    public function delete(string $url);
}
