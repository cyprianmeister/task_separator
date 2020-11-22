<?php

namespace App\Reader\Model;

use App\Common\Model\BaseTask;

class InputTask extends BaseTask
{
    /** @var int|string|null */
    protected $number;

    /** @var \DateTime|string|null */
    protected $dueDate;

    protected bool $isValid = true;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $property = lcfirst($key);
            $this->$property = $value;
        }
    }

    /**
     * @return int|string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTime|string|null
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
