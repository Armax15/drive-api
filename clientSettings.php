<?php



return [
    'appName'    => 'test',
    'scopes'     => [
        Google_Service_Drive::DRIVE
    ],
    'accessType' => 'offline',
    'authData'   => 'credentials-googleDrive.json',
    'tokenPath'  => 'token.json'
];