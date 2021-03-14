<?php

use core\API\APIService;
use core\API\APISettings\ClientSettings;
use core\API\GoogleDriveApi\ServiceFactory;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('CREDENTIALS_DIR')) {
    define('CREDENTIALS_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'credentials');
}

if (!defined('SETTINGS_DIR')) {
    define('SETTINGS_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'settings');
}

if (!file_exists(CREDENTIALS_DIR)) {
    mkdir(CREDENTIALS_DIR, 0600, true);
}

/** @var ServiceFactory $factory */
$factory = ServiceFactory::getInstance();

/** @var APIService $googleDriveServiceClass */
$googleDriveServiceClass = core\API\GoogleDriveApi\GoogleDriveApi::class;
$settings = ClientSettings::getInstance(
    SETTINGS_DIR . DIRECTORY_SEPARATOR . 'googleClientSettings.php'
);
$factory->addService($googleDriveServiceClass, $settings);



