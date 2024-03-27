<?php

namespace Websystems\WebPromptCreatorBundle\PromptContext;

class PromptContextMessage
{
    private int $id;
    private ?string $role = null;
    private ?string $content = null;

    public function __construct(int $id, ?string $role, ?string $content = null)
    {
        $this->id = $id;
        $this->role = $role;
        $this->content = $content;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}