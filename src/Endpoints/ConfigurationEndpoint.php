<?php

namespace Spotawheel\VumoApiClient\Endpoints;

use Illuminate\Http\Client\Response;
use Spotawheel\VumoApiClient\Support\ProcessorBuilder;

trait ConfigurationEndpoint 
{
    protected const CONFIGURATION_ENDPOINT = 'https://api.vumography.vumo.ai/v1/configuration';

    public function getConfiguration(string $configurationName = ''): Response
    {
        return $this->get(self::CONFIGURATION_ENDPOINT . "/{$configurationName}");
    }

    public function createOrUpdateConfiguration(string $configurationName, array $processors, string $type): Response
    {
        return $this->withQueryParameters(['type' => $type])
            ->post(self::CONFIGURATION_ENDPOINT . "/{$configurationName}", $processors);        
    }

    public function deleteConfiguration(string $configurationName): Response
    {
        return $this->delete(self::CONFIGURATION_ENDPOINT . "/{$configurationName}");
    }

    public function updateConfigurationInfo(string $configurationName, array $data): Response
    {
        return $this->post(self::CONFIGURATION_ENDPOINT . "/{$configurationName}/ui", $data);
    }

}