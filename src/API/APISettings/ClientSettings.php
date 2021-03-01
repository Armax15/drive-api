<?php

namespace core\API\APISettings;

use Exception;

class ClientSettings implements APISettingsInterface
{
    public const FIELD_APP_NAME    = 'appName';
    public const FIELD_SCOPES      = 'scopes';
    public const FIELD_AUTH_DATA   = 'authData';
    public const FIELD_ACCESS_TYPE = 'accessType';
    public const FIELD_TOKEN_PATH  = 'tokenPath';

    private string $appName;
    private array $scopes;
    private string $authData;
    private string $accessType;
    private string $tokenPath;

    private array $requiredFields = [
        self::FIELD_APP_NAME    => true,
        self::FIELD_SCOPES      => true,
        self::FIELD_AUTH_DATA   => true,
        self::FIELD_ACCESS_TYPE => true,
        self::FIELD_TOKEN_PATH  => true,
    ];

    /**
     * ClientSettings constructor.
     *
     * @param string $filePath
     *
     * @throws Exception
     */
    private function __construct(string $filePath)
    {
        if (!is_readable($filePath)) {
            throw new Exception("Settings file [{$filePath}] is unreadable.");
        }

        $config = require $filePath;
        $this->initConfig($config);
        $this->validate();
    }

    /**
     * @param array $config
     */
    private function initConfig(array $config): void
    {
        foreach ($config as $confName => $confValue) {
            if (!property_exists($this, $confName)) {
                continue;
            }

            $this->$confName = $confValue;
        }
    }

    /**
     * @throws Exception
     */
    private function validate(): void
    {
        $errors = '';
        foreach ($this->requiredFields as $propertyName => $isRequired) {
            if (!$isRequired) {
                continue;
            }

            if (empty($this->$propertyName)) {
                $errors .= "Missed [{$propertyName}] required property.\n";
            }
        }

        if (!empty($errors)) {
            throw new Exception($errors);
        }
    }

    /**
     * @param string $filePath
     *
     * @return static
     * @throws Exception
     */
    public static function getInstance(string $filePath): self
    {
        return new self($filePath);
    }

    /**
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName;
    }

    /**
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @return string
     */
    public function getAuthData(): string
    {
        return $this->authData;
    }

    /**
     * @return string
     */
    public function getAccessType(): string
    {
        return $this->accessType;
    }

    /**
     * @return string
     */
    public function getTokenPath(): string
    {
        return $this->tokenPath;
    }

}