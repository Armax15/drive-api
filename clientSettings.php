<?php

use root\ClientSettings;

define('CREDENTIALS_DIR', __DIR__ . '/credentials');

return [
    ClientSettings::FIELD_APP_NAME    => 'test',
    ClientSettings::FIELD_SCOPES      => [
        Google_Service_Drive::DRIVE
    ],
    ClientSettings::FIELD_ACCESS_TYPE => 'offline',
    ClientSettings::FIELD_AUTH_DATA   => CREDENTIALS_DIR . '/credentials-googleDrive.json',
    ClientSettings::FIELD_TOKEN_PATH  => CREDENTIALS_DIR . '/token.json'
];