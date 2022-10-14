<?php

declare(strict_types=1);

namespace MZierdt\Albion;

use CurlHandle;
use RuntimeException;

final class HttpClient
{
    private function call(string $url, string $method = 'get'): string
    {
        $curl = $this->initCurl($url);
        if ($method === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
        }
        $result = curl_exec($curl);
        if (is_bool($result)) {
            throw new RuntimeException('Couldn\'t perform cUrl Session.');
        }
        curl_close($curl);

        return $result;
    }

    private function initCurl(string $url): CurlHandle
    {
        $curl = curl_init($url);
        if (is_bool($curl)) {
            throw new RuntimeException('Couldn\'t perform cUrl Session.');
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }

    public function get(string $baseUrl, array $parameters = []): string
    {
        $url = $this->getUrl($baseUrl, $parameters);
        return $this->call($url);
    }

    public function post(string $url): string
    {
        return $this->call($url, 'post');
    }

    /*
     * $httpClient->get('http://www.google.com/search', ['q' => 'niedliche meerschweinchen', 'safe-search' => 'true'])
     * Anfrage-URL: https://www.google.com/search?q=+niedliche+meerschweinchen&safe-search=true
     */

    private function getUrl(string $baseUrl, array $parameters = []): string
    {
        if (! empty($parameters)) {
            $baseUrl .= '?';
            foreach ($parameters as $property => $parameter) {
                $baseUrl .= http_build_query([$property => $parameter]);
                next($parameters); // setzt den zeiger auf das nächste Element.
                if (current($parameters)) {
                    $baseUrl .= '&';
                }
            }
        }
        return $baseUrl;
    }

    public function getSpecial(mixed $gif)
    {
        $projectId = 1539;
        $url = sprintf('https://gitlab.mehrkanal.com/api/v4/projects/%d/repository/tree', $projectId);
        echo $url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($curl);
        curl_close($curl);
        $gitLapRepositoryTree = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        return $gitLapRepositoryTree;
    }
}
