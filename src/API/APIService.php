<?php


namespace core\API;


use core\API\APISettings\APISettingsInterface;

interface APIService
{

    public function __construct(APISettingsInterface $settings);

    public function getFiles(Params $params): ServiceResponse;

    public function createFiles(Params $params): ServiceResponse;

    public function deleteFiles(Params $params): ServiceResponse;

    public function updateFiles(Params $params): ServiceResponse;

    public function getServiceName(): string;
}