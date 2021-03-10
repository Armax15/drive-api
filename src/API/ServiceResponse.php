<?php


namespace core\API;


class ServiceResponse
{
    public const SUCCESS_STATUS      = 'success';
    public const FAIL_STATUS         = 'fail';
    private const AVAILABLE_STATUSES = [
        self::FAIL_STATUS    => true,
        self::SUCCESS_STATUS => true,
    ];

    private string $status;
    private array $data;

    public function __construct(array $data, string $status = self::SUCCESS_STATUS)
    {
        $this->validateStatus($status);

        $this->status = $status;
        $this->data = $data;
    }

    private function validateStatus(string $status)
    {
        if (empty(self::AVAILABLE_STATUSES[$status])) {
            throw new \InvalidArgumentException("Status [{$status}] doesn't supported.");
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}