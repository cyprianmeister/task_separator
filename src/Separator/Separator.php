<?php

namespace App\Separator;

use App\Common\Model\TaskCollection;
use App\Common\Model\TaskCollectionInterface;
use App\Separator\Handler\HandlerFactory;
use App\Separator\Handler\HandlerInterface;

class Separator
{
    private array $targetTypes;

    /** @var TaskCollectionInterface[] */
    private array $targets;

    private TaskCollectionInterface $errors;

    private array $handlerChain;

    private HandlerFactory $handlerFactory;

    public function __construct(array $targetTypes, array $handlerChain, HandlerFactory $handlerFactory)
    {
        $this->targetTypes = $targetTypes;
        $this->targets = $this->createTargets();
        $this->errors = new TaskCollection();
        $this->handlerChain = $handlerChain;
        $this->handlerFactory = $handlerFactory;
    }

    public function process(TaskCollectionInterface $sourceCollection): void
    {
        $sourceNum = $sourceCollection->count();
        for ($c = 0; $c < $sourceNum; $c++) {
            $inputTask = $sourceCollection->get($c);
            $solution = $this->createChain()->handle($inputTask);
            if (null !== $solution->getTask()) {
                $this->separate($solution);
            }
        }
    }

    public function getTargets(): array
    {
        return $this->targets;
    }

    public function getErrors(): TaskCollectionInterface
    {
        return $this->errors;
    }

    private function separate(Solution $solution): void
    {
        if (null === $type = $solution->getType()) {
            $this->errors->add($solution->getTask());
            return;
        }

        $this->targets[$type]->add($solution->getTask());
    }

    private function createChain(): HandlerInterface
    {
        $handlers = [];
        foreach (array_reverse($this->handlerChain) as $key => $handlerClass)
        {
            if ($key === 0) {
                $handlers[] = $this->handlerFactory->create($handlerClass);
                continue;
            }
            $handler = $this->handlerFactory->create($handlerClass);
            $handler->nextStep($handlers[$key-1]);
            $handlers[] = $handler;
        }

        return end($handlers);
    }

    private function createTargets(): array
    {
        $targets = [];
        foreach ($this->targetTypes as $type) {
            $targets[$type] = new TaskCollection();
        }
        return $targets;
    }
}
