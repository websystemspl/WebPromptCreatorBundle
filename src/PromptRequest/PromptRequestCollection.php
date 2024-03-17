<?php

namespace Websystems\WebPromptCreatorBundle\PromptRequest;

class PromptRequestCollection
{
    private array $promptRequests = [];

    public function addPromptRequest(PromptRequest $promptRequest): void
    {
        $this->promptRequests[] = $promptRequest;
    }

    public function getPromptRequests(): array
    {
        return $this->promptRequests;
    }

    public function findElementByUid(string $uid): PromptRequest
    {
        foreach($this->promptRequests as $promptRequest) {
            if($promptRequest->getUid() === $uid) {
                return $promptRequest;
            }
        }
    }

    public function getFinalRequest()
    {
        return end($this->promptRequests);
    }
}