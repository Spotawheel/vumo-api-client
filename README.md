# Vumography API client

Http client for [Vumography](https://vumo.ai/vumography/)

## Requirements

This package requires PHP 8.2

## Usage

This package is just a Http client. Please refer to the 
[official API documentation](https://docs.vumography.vumo.ai/index.html) for more information 
about the responses and the data accepted by the API.

### Getting started

Vumo uses access tokens to authorize its API calls. It also generates a refresh token which can be later used to re-issue an access token.

```php
// Initialize the client
$vumo = new \Spotawheel\VumoApiClient\Vumo($username, $password);
```

#### Authorization error

If the authorization fails a `\Spotawheel\VumoApiClient\Exceptions\VumoAuthException` will be thrown.

#### Successful authorization

After a successful authorization a refresh token will be returned. This token can be accessed and later stored by calling the getter.

```php
$refreshToken = $vumo->getRefreshToken();
```
This refresh token can be then supplied to the constructor to authorize the client.

 ```php
// Initialize the client
$vumo = new \Spotawheel\VumoApiClient\Vumo($username, $password, $refreshToken);
```

By default the client will try to use the refresh token to authorize the client. If the refresh token expires then the auth credentials will be used. If both fail a VumoException will be thrown.

## Responses

Uderneath this package is a wrapper for [illuminate/http](https://github.com/illuminate/http/tree/master). This package provides a lot of helpfull methods to deal with http responses. Documentation can be found [here](https://laravel.com/docs/10.x/http-client).

### Example of handling responses

```php
// Get all content
$response = $vumo->getContent();

// Check if the request was successfull
if ($response->ok()) {
    // Decode the returned json
    $data = $response->json();
}
```

