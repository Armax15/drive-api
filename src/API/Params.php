<?php


namespace core\API;


use InvalidArgumentException;

class Params
{

    private array $data = [];
    private array $availableParams;
    private bool $checkInputParams;

    public function __construct(array $availableParams = [])
    {
        $this->availableParams = array_flip($availableParams);
        $this->checkInputParams = !empty($this->availableParams);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param string $paramName
     *
     * @return mixed
     */
    public function getParam(string $paramName)
    {
        $this->checkParam($paramName);
        if (!isset($this->data[$paramName])) {
            throw new InvalidArgumentException("Param [{$paramName}] doesn't exist.");
        }

        return $this->data[$paramName];
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function addParams(array $params): Params
    {
        foreach ($params as $paramName => $paramValue) {
            $this->addParam($paramName, $paramValue);
        }

        return $this;
    }

    private function addParam(string $paramName, $value)
    {
        $this->checkParam($paramName);
        $this->data[$paramName] = $value;
    }

    /**
     * @param string $paramName
     *
     * @return bool
     */
    private function checkParam(string $paramName): bool
    {
        if ($this->checkInputParams && !isset($this->availableParams[$paramName])) {
            throw new InvalidArgumentException("Param [{$paramName}] not allowed to the client.");
        }

        return true;
    }
}