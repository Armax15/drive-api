<?php


namespace core\API;


use core\API\File\File;

interface APIService
{

    public function getFiles(Params $params): ServiceResponse;

    public function createFile(File $file): ServiceResponse;

    public function deleteFiles(Params $params): ServiceResponse;

    public function updateFile(File $file): ServiceResponse;

    public function getServiceName(): string;
}