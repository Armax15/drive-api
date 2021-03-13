<?php


namespace core\API\APISettings\APISettingsExceptions;


use Exception;

class ClientSettingsNotFound extends Exception
{

    public function __construct(string $file)
    {
        parent::__construct(
            "Client settings file [{$file}] not found."
        );
    }
}