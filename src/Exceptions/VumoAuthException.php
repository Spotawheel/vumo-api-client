<?php 

namespace Spotawheel\VumoApiClient\Exceptions;

use Exception;

class VumoAuthException extends Exception {

    public function __construct(string $message = 'Unknown error.')
    {
        parent::__construct($message);
    }

}