<?php

namespace Tests\Unit;

use Illuminate\Http\Client\Factory;
use PHPUnit\Framework\TestCase;
use Spotawheel\VumoApiClient\Vumo;

final class VumoTest extends TestCase
{

    public function testLogIn(): void
    {

        $vumo = new Vumo('username', 'password');

        $vumo->fake([
            'https://auth.vumo.ai/*' => Factory::response([
                "access_token" => "FAKE_JWT_TOKEN",
                "refresh_token" => "FAKE_JWT_REFRESH_TOKEN",
                "token_type" => "bearer",
                "expires_in" => 86400
            ], 200),

            'https://api.vumography.vumo.ai/v1/content' => Factory::response(null, 200)
        ]);

        $vumo->logIn();

        $vumo->getContent();

        [$content_request, $content_response] = $vumo->recorded()[1];

        $this->assertTrue($content_request->hasHeader('Authorization', 'Bearer FAKE_JWT_TOKEN'), 'Asserting that correct authorization header has been set');

        $this->assertEquals('FAKE_JWT_REFRESH_TOKEN', $vumo->getRefreshToken(), 'Asserting that refresh token has been set properly');
    }

    public function testRefreshingAccessToken(): void
    {
        $vumo = new Vumo(refreshToken: 'FAKE_JWT_REFRESH_TOKEN');

        $vumo->fake([
            'https://auth.vumo.ai/*' => Factory::response([
                "access_token" => "FAKE_JWT_TOKEN",
                "refresh_token" => "FAKE_JWT_REFRESH_TOKEN",
                "token_type" => "bearer",
                "expires_in" => 86400
            ], 200),

            'https://api.vumography.vumo.ai/v1/content' => Factory::response(null, 200)
        ]);

        $vumo->refreshAccessToken();

        $vumo->getContent();

        [$content_request, $content_response] = $vumo->recorded()[1];

        $this->assertTrue($content_request->hasHeader('Authorization', 'Bearer FAKE_JWT_TOKEN'), 'Asserting that correct authorization header has been set');

        $this->assertEquals('FAKE_JWT_REFRESH_TOKEN', $vumo->getRefreshToken(), 'Asserting that refresh token has been set properly');
    }

}