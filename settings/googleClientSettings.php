<?php

use core\API\APISettings\ClientSettings;

$subDir = 'googleDrive';

return [
    ClientSettings::FIELD_APP_NAME    => 'test',
    ClientSettings::FIELD_SCOPES      => [
        Google_Service_Drive::DRIVE
    ],
    ClientSettings::FIELD_ACCESS_TYPE => 'offline',
    ClientSettings::FIELD_AUTH_DATA   => CREDENTIALS_DIR . "/{$subDir}/credentials.json",
    ClientSettings::FIELD_TOKEN_PATH  => CREDENTIALS_DIR . "/{$subDir}/token.json"
];