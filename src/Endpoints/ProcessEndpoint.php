<?php 

namespace Spotawheel\VumoApiClient\Endpoints;

use Illuminate\Http\Client\Response;

trait ProcessEndpoint 
{
    protected const PROCESS_ENDPOINT = 'https://api.vumography.vumo.ai/v1/process';

    public function processSingleImage(
        string $configurationName, 
        string $imageContents, 
        bool $throtling = false, 
        string $extention = 'jpeg'
    ): Response
    {
        return $this->withBody($imageContents, "image/{$extention}")
            ->withQueryParameters(['throtling' => $throtling])
            ->post(self::PROCESS_ENDPOINT . "/single/{$configurationName}");
    }

    public function processSingleImageWithDetails(
        string $configurationName, 
        string $imageContents, 
        bool $throtling = false, 
        string $extention = 'jpeg'
    ): Response
    {
        return $this->withBody($imageContents, "image/{$extention}")
            ->withQueryParameters(['throtling' => $throtling])
            ->post(self::PROCESS_ENDPOINT . "/single/{$configurationName}");
    }

    public function processSingleImageAsync(
        string $configurationName, 
        string $imageContents, 
        string $urlToResponse, 
        bool $throtling = false, 
        string $extention = 'jpeg'
    ): Response
    {
        return $this->withBody($imageContents, "image/{$extention}")
            ->withQueryParameters([
                'throttling' => $throtling,
                'urlToResponse' => $urlToResponse,
            ])
            ->post(self::PROCESS_ENDPOINT . "/single/{$configurationName}/async");
    }

    public function processSingleUrlWithDetailsAsync(
        string $configurationName,
        string $imageUrl, 
        string $urlToResponse, 
        array $headers = [], 
        bool $throtling = false
    ): Response
    {
        return $this->withQueryParameters(['throtling' => $throtling])
            ->post(self::PROCESS_ENDPOINT . "/single/url/{$configurationName}/async/details", [
                'endpoint' => [
                    'headers' => $headers,
                    'url' => $urlToResponse
                ],
                'urlWithImage' => $imageUrl
            ]);
    }
    
    public function processMultipleImagesWithDetails(string $configurationName, array $images): Response
    {
        return $this->withBody($images, 'multipart/form-data')
            ->post(self::PROCESS_ENDPOINT . "/multiple/{$configurationName}/details");
    }

    public function processMultipleUrlsWithDetails(string $configurationName, array $urls): Response
    {
        return $this->post(self::PROCESS_ENDPOINT . "/multiple/urls/{$configurationName}/details", $urls);
    }

    public function processMultipleUrlsWithDetailsAsync(
        string $configurationName, 
        array $imageUrls, 
        string $urlToResponse, 
        array $headers = []
    ): Response
    {
        return $this->call('POST', self::PROCESS_ENDPOINT . "/multiple/urls/{$configurationName}/async/details", [
            'json' => [
                'endpoint' => [
                    'headers' => $headers,
                    'url' => $urlToResponse,
                ],
                'urlsWithImage' => $imageUrls,
            ]
        ]);
    }

}