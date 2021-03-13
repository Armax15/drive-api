<?php


namespace core\API\APISettings;


interface APISettingsInterface
{
    public static function getInstance(string $filePath): self;
}