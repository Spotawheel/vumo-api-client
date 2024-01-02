<?php

namespace Spotawheel\VumoApiClient;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Spotawheel\VumoApiClient\Endpoints\ConfigurationEndpoint;
use Spotawheel\VumoApiClient\Endpoints\ContentEndpoint;
use Spotawheel\VumoApiClient\Endpoints\ProcessEndpoint;
use Spotawheel\VumoApiClient\Exceptions\VumoAuthException;

class Vumo extends Factory 
{
    use ContentEndpoint;
    use ConfigurationEndpoint;
    use ProcessEndpoint;

    const AUTH_URL = 'https://auth.vumo.ai';

    protected string $accessToken;
    protected string $refreshToken;
    protected string $username;
    protected string $password;
    protected string $system;

    public function __construct(string $username, string $password, string $refreshToken = '', string $system = 'autography') 
    {
        parent::__construct();

        $this->username = $username;
        $this->password = $password;
        $this->system = $system;
        $this->refreshToken = $refreshToken;
        $this->accessToken = '';
    }

    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()
            ->withToken($this->accessToken);
    }

    public function authorize(): Vumo
    {
        
        try {
            
            if (empty($this->refreshToken)) {
                throw new VumoAuthException('No refresh token supplied.');
            }

            $this->refreshAccessToken();

        } catch (VumoAuthException $e) {

            $this->logIn();

        }

        return $this;
    }

    private function logIn(): void
    {
        $response = $this->post(self::AUTH_URL . "/login", [
            'username' => $this->username,
            'password' => $this->password,
            'system' => $this->system,
        ]);
        
        $data = $response->json();

        if (!$response->ok()) {
            throw new VumoAuthException($data['message'] ?? null);
        }

        $this->accessToken = $data['access_token'];
        $this->refreshToken = $data['refresh_token'];
    
    }

    private function refreshAccessToken(): void
    {
        $response = $this->post(self::AUTH_URL . "/refresh", [
            'refresh_token' => $this->refreshToken,
        ]);

        $data = $response->json();

        if (!$response->ok()) {
            throw new VumoAuthException($data['message'] ?? null);
        }

        $this->accessToken = $data['access_token'];
    }

    public function getRefreshToken(): string 
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): Vumo
    {
        $this->refreshToken = $refreshToken;
        
        return $this;
    }

}