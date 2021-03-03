<?php


namespace core\API;


class Params
{

    private string $query;

    public function toArray(): array
    {
        return [
            'q' => $this->query
        ];
    }
}