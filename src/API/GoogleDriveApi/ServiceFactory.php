<?php


namespace core\API\GoogleDriveApi;


use core\API\APIService;
use core\API\APISettings\ClientSettings;
use core\Helpers\SingletonTrait;

class ServiceFactory
{
    use SingletonTrait;

    /**
     * @var APIService[]
     */
    private array $serviceList = [];

    /**
     * @param string         $serviceClass
     * @param ClientSettings $settings
     */
    public function addService(string $serviceClass, ClientSettings $settings): void
    {
        $serviceInterface = APIService::class;
        $interfacesOfService = class_implements($serviceClass);
        if (!isset($interfacesOfService[$serviceInterface])) {
            throw new \InvalidArgumentException("Provided service class [{$serviceClass}] must implements [{$serviceInterface}] interface.");
        }

        /** @var APIService $serviceClass */
        if (!isset($this->serviceList[$serviceClass::getServiceName()])) {
            $this->serviceList[$serviceClass::getServiceName()] = [
                'class' => $serviceClass,
                'settings' => $settings
            ];
        }
    }

    /**
     * @param string $serviceName
     *
     * @return APIService
     * @throws ServiceNotFoundException
     */
    public function getService(string $serviceName): APIService
    {
        if (isset($this->serviceList[$serviceName])) {
            $serviceClass = $this->serviceList[$serviceName]['class'];
            $settings = $this->serviceList[$serviceName]['settings'];

            return new $serviceClass($settings);
        }

        throw new ServiceNotFoundException($serviceName);
    }
}