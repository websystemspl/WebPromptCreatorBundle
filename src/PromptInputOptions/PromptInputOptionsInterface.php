<?php

namespace Websystems\WebPromptCreatorBundle\PromptInputOptions;

interface PromptInputOptionsInterface
{
    public function configureOptions(): array;
    public function setInputOptions(?array $inputOptions): void;
    public function getInputOptions(): ?array;
    public function addInputOption(string $key, string $value): void;
    public function removeInputOption(string $key): void;
    public function getOptionByKey(string $key): string;
    public function updateOptionByKey(string $key, string $value): void;
}