<?php


namespace core\API\GoogleDriveApi;


use Exception;

class ServiceNotFoundException extends Exception
{

    public function __construct(string $serviceName)
    {
        parent::__construct(
            "Service [{$serviceName}] has not been initialized yet."
        );
    }
}