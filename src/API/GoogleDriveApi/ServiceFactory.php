<?php


namespace core\API\GoogleDriveApi;


use core\API\APIService;

class ServiceFactory
{
    /**
     * @var APIService[]
     */
    private array $serviceList = [];

    public function addService(APIService $service): void
    {
        if (!isset($this->serviceList[$service->getServiceName()])) {
            $this->serviceList[$service->getServiceName()] = $service;
        }
    }

    public function getService(string $serviceName): APIService
    {
        if (isset($this->serviceList[$serviceName])) {
            return $this->serviceList[$serviceName];
        }

        throw new ServiceNotFoundException($serviceName);
    }
}