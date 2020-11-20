<?php

namespace App\Writer\Model;

use App\Common\Model\BaseTask;

class OutputTask extends BaseTask
{
    protected static string $type;

    protected string $status;

    protected string $comments;

    protected \DateTime $createdAt;

    public function setStatus(string $status): OutputTask
    {
        $this->status = $status;
        return $this;
    }

    public function setComments(string $comments): OutputTask
    {
        $this->comments = $comments;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt): OutputTask
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setDescription(string $description): BaseTask
    {
        $this->description = $description;
        return $this;
    }

    public function setDueDate(?\DateTime $dueDate): BaseTask
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function setPhone(string $phone): BaseTask
    {
        $this->phone = $phone;
        return $this;
    }

    protected function getType(): string
    {
        return static::$type ?: (new \ReflectionClass(static::class))->getName();
    }
}
