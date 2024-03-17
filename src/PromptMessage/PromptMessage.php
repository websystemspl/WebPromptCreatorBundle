<?php

namespace Websystems\WebPromptCreatorBundle\PromptMessage;

class PromptMessage
{
    private ?string $role = null;
    private ?string $content = null;

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content
        ];
    }
}