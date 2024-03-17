<?php

namespace Websystems\WebPromptCreatorBundle\PromptRequest;

class PromptRequest
{
    private ?string $uid = null;
    private ?array $input = null;
    private string $output = "";

    public function setInput(array $input): void
    {
        $this->input = $input;
    }

    public function setOutput(string $output): void
    {
        $this->output = $output;
    }

    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}