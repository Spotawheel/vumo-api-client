# Vumography API client

Http client for [Vumography](https://vumo.ai/vumography/). This package uses Laravel's Http client but can be used in any PHP project.

## Requirements

This package requires PHP 8.2

## Installation

The following must be added to the repositories array in the composer.json of your project.

```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/Spotawheel/vumo-api-client.git"
    }
]
```

Then simply run

```
composer require spotawheel/vumo-api-client
```

## Usage

This package is just a Http client. Please refer to the 
[official API documentation](https://docs.vumography.vumo.ai/index.html) for more information 
about the responses and the data accepted by the API.

### Getting started

Vumo uses access tokens to authorize its API calls. It also generates a refresh token which can be later used to re-issue an access token.

```php
// Initialize the client
$vumo = new \Spotawheel\VumoApiClient\Vumo($username, $password);

// Authorize the client
$vumo->authorize();
```

#### Authorization error

The `authorize` function will try to get the access token by using the refresh token. If you have not specified a refresh token or it has expired then it will try to log in using the supplied credentials. If both refreshing and login fail then a `\Spotawheel\VumoApiClient\Exceptions\VumoAuthException` will be thrown.

#### Successful authorization

After a successful authorization a refresh token will be returned. This token can be accessed and later stored by calling the getter.

```php
$refreshToken = $vumo->getRefreshToken();
```
Refresh token's can be then supplied to the constructor to authorize the client.

 ```php
$vumo = new \Spotawheel\VumoApiClient\Vumo($username, $password, $refreshToken);

$vumo->authorize();
```

The client can also be initialized with only the refresh token.

```php
$vumo = new \Spotawheel\VumoApiClient\Vumo(refreshToken: 'FAKE_JWT_REFRESH_TOKEN');

$vumo->authorize();
```

Be aware that the log in will fail when the refresh token expires.

#### Re-authorize client

If the access token expires the `authorize` method can be called to re-authorize the client. According to the docs access tokens expire after a day.

```php
$vumo->authorize();
```

## Handling Responses

This client is a wrapper for [illuminate/http](https://github.com/illuminate/http/tree/master)'s client. This package provides a lot of helpfull methods to deal with http responses. Documentation can be found [here](https://laravel.com/docs/10.x/http-client).

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

## Available methods

Every vumography endpoint has be mapped to its corresponding method. For more information on what data these endpoint expect consult the documentation.

### Content

```php
// Get all content
$response = $vumo->getContent();

// Get a specific content by its name
$response = vumo->getContent('content-name');

// Create or update content (the default extention parameter is jpeg)
$image = file_get_contents('image.png');
$response = $vumo->createOrUpdateContent('content-name', 'BACKGROUND_SINGLE', $image, 'png');

// Delete content
$response = $vumo->deleteContent('content-name');
```

### Configuration

On information on how to build configurations please consult the official documentation.

```php
// Get all configurations
$response = $vumo->getConfiguration();

// Get a specific configuration
$response = $vumo->getConfiguration('configuration-name');

// Create or update a configuration
$response = $vumo->createOrUpdateConfiguration('configuration-name', $processors_array, 'EXTERIOR');

// Update configurations additional info
$response = $vumo->updateConfigurationInfo('configuration-name', $info_array);

// Delete configuration
$response = $vumo->deleteConfiguration('configuration-name');

```

### Processing images

```php
$image = file_get_contents('file.jpeg');
$throtling = true;

// Process single image by suplying image contents as a string
$response = $vumo->processSingleImage('configuration-name', $image);

// Extention and throtling can also be specified
$response = $vumo->processSingleImage('configuration-name', $image, 'jpeg', $throtling);

// Same as before but returns a more detailed response
$response = $vumo->processSingleImageWithDetails('configuration-name', $image, 'jpeg', $throtling);

// Process a single image asynchronously with specifying a webhook url
$response = $vumo->processSingleImageAsync('configuration-name', 'https://response_url_here.test', $image, 'jpeg', $throtling);

// Process a single image asynchronously by supplying the image url
$response = $vumo->processSingleUrlWithDetailsAsync('configuration-name', 'https://response_url_here.test', 'https://myimage.jpg', $headers, $throtling);

// Process multiple images
$response = $vumo->processMultipleImagesWithDetails('cofiguration-name', $images_array);

// Process multiple images URLs
$response = $vumo->processMultipleUrlsWithDetails('cofiguration-name', $urls_array);

// Process multiple images asynchronously
$response = $vumo->processMultipleUrlsWithDetailsAsync('cofiguration-name', 'https://response_url_here.test', $urls_array, $headers);

```

Throtling and extention are optional parameters that can be omitted. Their default values are:
`$extension = 'jpeg'`
</br>
`$throtling = false`
