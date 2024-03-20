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
```sh
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
```sh
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
```sh
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
```sh
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['admin/web_prompt_creator.html.twig', '@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }   
```
Configure assets by adding js and css files
```sh
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
documentation in progress...
