<?php


namespace core\API;


interface APIService
{

    public function getFiles(Params $params): ServiceResponse;

    public function createFiles(Params $params): ServiceResponse;

    public function deleteFiles(Params $params): ServiceResponse;

    public function updateFiles(Params $params): ServiceResponse;

    public function getServiceName(): string;
}