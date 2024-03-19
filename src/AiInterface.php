<?php

namespace Websystems\WebPromptCreatorBundle;

interface AiInterface
{
    public function supports($type): bool;
    public function send(array $messages): ?array;
}