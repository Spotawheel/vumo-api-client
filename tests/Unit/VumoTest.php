<?php

namespace Tests\Unit;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use PHPUnit\Framework\TestCase;
use Spotawheel\VumoApiClient\Vumo;

final class VumoTest extends TestCase
{

    public function testAuthorization()
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

        $vumo->authorize();

        $vumo->getContent();

        [$content_request, $content_response] = $vumo->recorded()[1];

        $this->assertTrue($content_request->hasHeader('Authorization', 'Bearer FAKE_JWT_TOKEN'));

        $this->assertEquals('FAKE_JWT_REFRESH_TOKEN', $vumo->getRefreshToken());
    }

}