<?php


use root\ClientSettings;

return [
    ClientSettings::FIELD_APP_NAME    => 'test',
    ClientSettings::FIELD_SCOPES      => [
        Google_Service_Drive::DRIVE
    ],
    ClientSettings::FIELD_ACCESS_TYPE => 'offline',
    ClientSettings::FIELD_AUTH_DATA   => 'credentials-googleDrive.json',
    ClientSettings::FIELD_TOKEN_PATH  => 'token.json'
];