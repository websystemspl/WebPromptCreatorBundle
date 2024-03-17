<?php

namespace Websystems\WebPromptCreatorBundle\PromptInputOptions;

use Websystems\WebPromptCreatorBundle\PromptInputOptions\PromptInputOptionsInterface;

abstract class PromptInputOptions implements PromptInputOptionsInterface
{
    protected ?array $inputOptions = null;

    public function __construct()
    {
        $this->inputOptions = $this->configureOptions();
    }


    public function configureOptions(): array
    {
        return [];
    }

    public function setInputOptions(?array $inputOptions): void
    {
        $this->inputOptions = $inputOptions;
    }

    public function getInputOptions(): ?array
    {
        return $this->inputOptions;
    }

    public function addInputOption(string $key, string $value): void
    {
        $this->inputOptions[$key] = $value;
    }

    public function removeInputOption(string $key): void
    {
        unset($this->inputOptions[$key]);
    }

    public function getOptionByKey(string $key): string
    {
        return $this->inputOptions[$key];
    }

    public function updateOptionByKey(string $key, string $value): void
    {
        $this->inputOptions[$key] = $value;
    }
}