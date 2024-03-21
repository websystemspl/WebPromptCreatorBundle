# Web Prompt Creator
## _for Symfony & EasyAdmin_

WPC is drag & drop builder for OpenAi (or other) prompts.

![webpromptcreator](https://github.com/websystemspl/WebPromptCreatorBundle/assets/19324646/845a3a28-0cbf-4242-81c6-5c30f001ed4a)

By WPC you can design advanced prompts which can send several request before final response

## Requirements

- Symfony UX Vue
https://symfony.com/bundles/ux-vue/current/index.html

## Installation

Install WPC
```sh
composer require websystems/web-prompt-creator-bundle
```

Create service which will override input data
$options will be used in input widget and values will be updated before send to AI
```php
namespace App\Service;

use Websystems\WebPromptCreatorBundle\PromptInputOptions\PromptInputOptions;

class PromptInputOptionsService extends PromptInputOptions
{
    public function configureOptions(): array
    {
        $options = [
            'input_content' => '',
            'other_input_data' => '',
        ];

        return $options;
    }
}
```

Configure EasyAdmin crud controller and add service
Add PromptField and set custom option named "input" with service options
```php
    public function __construct(
        ...
        private PromptInputOptionsService $promptInputOptionsService,
    )
    {
    }
    ...
    public function configureFields(string $pageName): iterable
    {
        return [
            PromptField::new('prompt')
                ->setCustomOption("input", $this->promptInputOptionsService->getInputOptions())->hideOnIndex(),        
```

Create override template for this field in templates/admin for example. web_prompt_creator.html.twig
In {% block _Prompt_prompt_widget %} Prompt is a entity name and _prompt is entity property name
arguments:
- element_hidden - if _true_ then block will show <input type="hidden"... field, if _false_ then <textarea>
- element_value - field value
- element_id - field id
- element_name - field name
- input - "input" data from your service
```twig
{% block _Prompt_prompt_widget %}
    <div {{ vue_component('WebPromptCreator', {
        'element_hidden': true,
        'element_value': form.vars.value, 
        'element_id': form.vars.id, 
        'element_name': form.vars.full_name, 
        'input': form.vars.ea_vars.field.customOptions.get('input'),
        }) 
    }}></div>
{% endblock %}
```
Add form themes to crud controller
```php
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['admin/web_prompt_creator.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }   
```
Configure assets by adding js and css files
```php
    ...
    use Websystems\WebPromptCreatorBundle\Asset\AssetPackage;
    ...
    public function __construct(
        ...
        protected RequestStack $requestStack,
    )
    {
    }
    ...
    public function configureAssets(Assets $assets): Assets
    {
        $assetPackage = new AssetPackage($this->requestStack);

        return $assets
            ->addJsFile($assetPackage->getUrl('437.js'))
            ->addJsFile($assetPackage->getUrl('runtime.js'))
            ->addJsFile($assetPackage->getUrl('builder.js'))
            ->addCssFile($assetPackage->getUrl('437.css'))
            ->addCssFile($assetPackage->getUrl('builder.css'))
        ;
    }   
```

## Usage

WPC creates JSON data which is stored in database.
There are functions to handle this JSON and process to final response.

If you have an API class which is used to send requests to AI it must implements interface:
```php
...
use Websystems\WebPromptCreatorBundle\AiInterface;

class OpenAi implements AiInterface
{
    public function supports($type): bool
    {
        return is_a($this, $type, true);
    }   

    public function send(array $messages): ?array
    {
        ...
        
        return [
            'content' => $response['choices'][0]['message']['content'],
            'data' => $response,
        ];        
    }
```


If you want to process for example in your controller, you must use dependency injection to get services:
```php
    ...
    public function __construct(
        private OpenAi $openAi,
        private WebPromptCreator $webPromptCreator,
        private PromptInputOptionsService $promptInputOptionsService,
    )
    {
    }
```
And add important things to your webPromptCreator instance
createRequests() - will send requests to ai and process all
```php
        $json = $someRepository->find(...)
        $contentToProcess = "Some content to process";

        $this->webPromptCreator->setPromptData(json_decode($json, true));
        $this->webPromptCreator->setAiService($this->openAi);
        $this->promptInputOptionsService->updateOptionByKey('input_content', $contentToProcess);
        ...
        $this->webPromptCreator->setInputData($this->promptInputOptionsService);
        $this->webPromptCreator->createRequests();
```
To read final response
```php
$this->webPromptCreator->getFinalResponse();
```
To read response by UID of request:
```php
getResponseOfRequestByUid($uid)
```
To read response data by UID of request widget:
```php
getResponseDataOfRequestByUid($uid)
```

```php
getRequestCollection()
getPromptRequestCollectionAsArrayConversation(bool $withAnswers = false)
```

showDummyCoversation is like createRequests() but will not send requests to AI and create PromptRequestCollection with dummy data
```php
showDummyCoversation(string $responseText = "This is a dummy response")
```




