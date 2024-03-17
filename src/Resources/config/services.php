<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Websystems\WebPromptCreatorBundle\Asset\AssetPackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Websystems\WebPromptCreatorBundle\WebPromptCreatorService;

return static function (ContainerConfigurator $container) {
    $services = $container->services()
        ->defaults()->private()
    ;

    $services
        ->set(AssetPackage::class)
            ->arg(0, service('request_stack'))
            ->tag('assets.package', ['package' => AssetPackage::PACKAGE_NAME])
    ;

    $services
        ->set(WebPromptCreatorService::class)
    ;
};
