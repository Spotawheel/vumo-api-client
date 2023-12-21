<?php

namespace Spotawheel\VumoApiClient\Endpoints;

use Illuminate\Http\Client\Response;

trait ConfigurationEndpoint 
{
    protected const CONFIGURATION_ENDPOINT = 'https://api.vumography.vumo.ai/v1/configuration';

    public function getConfiguration(string $configuration = ''): Response
    {
        return $this->get(self::CONFIGURATION_ENDPOINT . "/{$configuration}");
    }

    public function createOrUpdateConfiguration()
    {
        
    }

}