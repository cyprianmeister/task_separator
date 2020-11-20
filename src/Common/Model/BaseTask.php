<?php

namespace App\Common\Model;

abstract class BaseTask
{
    protected string $description;

    protected ?\DateTime $dueDate;

    protected string $phone;
}
