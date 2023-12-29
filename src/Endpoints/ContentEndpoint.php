<?php

namespace Spotawheel\VumoApiClient\Endpoints;

use Illuminate\Http\Client\Response;

trait ContentEndpoint 
{
    protected const CONTENT_ENDPOINT = 'https://api.vumography.vumo.ai/v1/content';

    public function getContent(string $contentName = ''): Response
    {
        return $this->get(self::CONTENT_ENDPOINT . "/{$contentName}");
    }

    public function createOrUpdateContent(
        string $contentName, 
        string $contentType, 
        string $imageContents, 
        string $extention = 'jpeg'
    ): Response
    {
        return $this->withBody($imageContents, "image/{$extention}")->post(self::CONTENT_ENDPOINT . "/upload/{$contentName}/{$contentType}");
    }

    public function deleteContent(string $contentName): Response
    {
        return $this->delete(self::CONTENT_ENDPOINT . "/{$contentName}");
    }

}