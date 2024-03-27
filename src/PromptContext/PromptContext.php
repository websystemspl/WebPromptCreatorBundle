<?php

namespace Websystems\WebPromptCreatorBundle\PromptContext;

use Websystems\WebPromptCreatorBundle\PromptContext\PromptContextMessage;


class PromptContext
{
    protected ?array $context = null;

    public function add(PromptContextMessage $message)
    {
        $this->context[$message->getId()] = $message;
    }

    public function all(): array
    {
        return $this->context;
    }

    public function find(int $id): ?PromptContextMessage
    {
        return $this->context[$id] ?? null;
    }

    public function remove(PromptContextMessage $promptContextMessage): void
    {
        unset($this->context[$promptContextMessage->getId()]);
    }

    public function clear(): void
    {
        $this->context = null;
    }
}