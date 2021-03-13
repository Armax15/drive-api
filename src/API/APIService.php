<?php


namespace core\API;


use core\API\File\File;

interface APIService
{

    public function searchFiles(Params $params): ServiceResponse;

    public function createFile(File $file): ServiceResponse;

    public function deleteFile(File $file): ServiceResponse;

    public function updateFile(File $file): ServiceResponse;

    public function downloadFile(File $file): ServiceResponse;

    public static function getServiceName(): string;
}