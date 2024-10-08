<?php

namespace Websystems\WebPromptCreatorBundle;

use Websystems\WebPromptCreatorBundle\AiInterface;
use Websystems\WebPromptCreatorBundle\PromptContext\PromptContext;
use Websystems\WebPromptCreatorBundle\PromptMessage\PromptMessage;
use Websystems\WebPromptCreatorBundle\PromptRequest\PromptRequest;
use Websystems\WebPromptCreatorBundle\PromptRequest\PromptRequestCollection;
use Websystems\WebPromptCreatorBundle\PromptInputOptions\PromptInputOptionsInterface;

class WebPromptCreator
{
    private ?AiInterface $aiService = null;
    private ?PromptInputOptionsInterface $inputData = null;
    private ?PromptContext $context = null;
    private array $promptData = [];
    private ?PromptRequestCollection $requestCollection = null;
    private ?int $error = null;

    public function __construct(?AiInterface $aiService = null)
    {
        $this->aiService = $aiService;
    }

    public function setAiService(AiInterface $aiService): void
    {
        $this->aiService = $aiService;
    }

    public function setInputData(PromptInputOptionsInterface $inputData): void
    {
        $this->inputData = $inputData;
    }

    public function getContext(): ?PromptContext
    {
        return $this->context;
    }

    public function setContext(?PromptContext $context): void
    {
        $this->context = $context;
    }

    public function setPromptData(?array $promptData): void
    {
        $this->promptData = $promptData;
    }

    public function createRequests()
    {
        $this->requestCollection = new PromptRequestCollection();

        foreach($this->promptData as $prompt) {
            $request = new PromptRequest();
            $request->setUid($prompt['uid']);
            $request->setInput($this->generateMessages($prompt));
            $messageResponse = $this->sendMessage($request->getInput());

            if(isset($messageResponse['error'])) {
                $this->error = intval($messageResponse['error']);
                return;
            }

            $request->setOutput($messageResponse['content']);
            $request->setOutputData($messageResponse['data']);
            $this->requestCollection->addPromptRequest($request);
        }
    }

    public function getRequestCollection(): ?PromptRequestCollection
    {
        return $this->requestCollection;
    }

    public function setRequestCollection(PromptRequestCollection $requestCollection): void
    {
        $this->requestCollection = $requestCollection;
    }

    public function getFinalResponse(): string
    {
        return $this->requestCollection->getFinalRequest()->getOutput();
    }    

    public function getResponseOfRequestByUid(string $uid): string
    {
        return $this->requestCollection->findElementByUid($uid)->getOutput();
    }

    public function getResponseDataOfRequestByUid(string $uid): array
    {
        return $this->requestCollection->findElementByUid($uid)->getOutputData();
    }

    private function generateMessages(array $request): array
    {
        $messages = [];

        foreach($request['widgets'] as $widget) {
            $content = "";

            if(!isset($widget['widgets']) && $widget['id'] === "context") {
                $contextConversation = $this->context->all();
                foreach($contextConversation as $contextConversationMessage) {
                    $promptMessage = new PromptMessage();
                    $promptMessage->setRole($contextConversationMessage->getRole());
                    $promptMessage->setContent($contextConversationMessage->getContent());
                    $messages[] = $promptMessage;
                }
            }

            if(isset($widget['widgets'])) {
                $content = $this->generateConcatenatedContentFromChildWidgets($widget);
            }

            if(!isset($widget['widgets']) && isset($widget['settings']['content'])) {
                $content = $widget['settings']['content'];
            }

            if(!isset($widget['widgets']) && isset($widget['settings']['relation'])) {
                $content = $this->requestCollection->findElementByUid($widget['settings']['relation'])->getOutput();
            }

            if(!isset($widget['widgets']) && isset($widget['settings']['input'])) {
                $content = $this->inputData->getOptionByKey($widget['settings']['input']);
            }            

            if($widget['id'] !== "context") {
                $promptMessage = new PromptMessage();
                $promptMessage->setRole($widget['settings']['role']);
                $promptMessage->setContent($content);
                $messages[] = $promptMessage;
            }
        }

        return $messages;
    }

    private function generateConcatenatedContentFromChildWidgets(array $widget): string
    {
        $content = "";

        foreach($widget['widgets'] as $childWidget) {
            if(isset($childWidget['widgets'])) {
                $content .= $this->generateConcatenatedContentFromChildWidgets($childWidget, $this->requestCollection);
            }

            if(!isset($childWidget['widgets']) && isset($childWidget['settings']['relation'])) {
                $content .= 
                    $this->addNewLines($childWidget['settings']['new_lines_before']) . 
                    $this->requestCollection->findElementByUid($childWidget['settings']['relation'])->getOutput() . 
                    $this->addNewLines($childWidget['settings']['new_lines_after'])
                ;
            }

            if(!isset($childWidget['widgets']) && isset($childWidget['settings']['input'])) {
                $content .= 
                    $this->addNewLines($childWidget['settings']['new_lines_before']) . 
                    $this->inputData->getOptionByKey($childWidget['settings']['input']) .
                    $this->addNewLines($childWidget['settings']['new_lines_after'])
                ;
            }

            if(!isset($childWidget['widgets']) && isset($childWidget['settings']['content'])) {
                $content .= 
                    $this->addNewLines($childWidget['settings']['new_lines_before']) . 
                    $childWidget['settings']['content'] . 
                    $this->addNewLines($childWidget['settings']['new_lines_after'])
                ;
            }
        }

        return $content;
    }

    private function addNewLines(int $numberOfLines): string
    {
        $newLines = "";
        for($i = 0; $i < $numberOfLines; $i++) {
            $newLines .= "\n";
        }

        return $newLines;
    }

    private function sendMessage(array $messages): ?array
    {
        $conversation = [];
        foreach($messages as $message) {
            if($message instanceof PromptMessage) {
                $conversation[] = $message->toArray();
            }
        }
        $response = $this->aiService->send($conversation);

        return $response;
    }

    public function getPromptRequestCollectionAsArrayConversation(bool $withAnswers = false)
    {
        $conversation = [];
        foreach($this->requestCollection->getPromptRequests() as $request) {
            foreach($request->getInput() as $message) {
                $conversation[] = $message->toArray();
            }

            if(true === $withAnswers) {
                $conversation[] = [
                    'role' => 'assistant',
                    'content' => $request->getOutput()
                ];
            }
        }

        return $conversation;
    }

    public function showDummyCoversation(string $responseText = "This is a dummy response"): void
    {
        $this->requestCollection = new PromptRequestCollection();

        foreach($this->promptData as $prompt) {
            $request = new PromptRequest();
            $request->setUid($prompt['uid']);
            $request->setInput($this->generateMessages($prompt));
            $request->setOutput($responseText);
            $this->requestCollection->addPromptRequest($request);
        }
    }

    public function hasError(): bool
    {
        return null !== $this->error;
    }

    public function getErrorCode(): ?int
    {
        return $this->error;
    }
}