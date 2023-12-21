<?php 

namespace Spotawheel\VumoApiClient\Endpoints;

use Illuminate\Http\Client\Response;

trait ProcessEndpoint 
{
    protected const PROCESS_ENDPOINT = 'https://api.vumography.vumo.ai/v1/process';

    public function processSingleImage(string $configurationName, string $imageContents, bool $throtling = false): Response
    {
        return $this->withBody($imageContents)
            ->withQueryParams(['throtling' => $throtling])
            ->post(self::PROCESS_ENDPOINT . "/single/{$configurationName}");
    }

    public function processSingleImageWithDetails(string $configurationName, string $imageContents, bool $throtling = false): Response
    {
        return $this->withBody($imageContents)
            ->withQueryParams(['throtling' => $throtling])
            ->post(self::PROCESS_ENDPOINT . "/single/{$configurationName}");
    }

    public function processSingleImageAsync(string $configurationName, string $imageContents, string $urlToResponse, bool $throtling = false): Response
    {
        return $this->withBody($imageContents)
            ->withQueryParams([
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
        return $this->withQueryParams(['throtling' => $throtling])
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
        return $this->withBody($images)
            ->post(self::PROCESS_ENDPOINT . "/multiple/{$configurationName}/details");
    }

    public function processMultipleUrlsWithDetails(string $configurationName, array $urls): Response
    {
        return $this->withBody($urls)
            ->post(self::PROCESS_ENDPOINT . "/multiple/urls/{$configurationName}/details");
    }

    public function processMultipleUrlsWithDetailsAsync(string $configurationName, array $imageUrls, string $urlToResponse, array $headers = []): Response
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