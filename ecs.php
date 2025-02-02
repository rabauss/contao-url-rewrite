<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__.'/vendor/contao/easy-coding-standard/config/self.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SKIP, [
        '*/Resources/contao/*',
        MethodChainingIndentationFixer::class => [
            '*/DependencyInjection/Configuration.php',
        ],
    ]);

    $date = date('Y');

    $services = $containerConfigurator->services();
    $services
        ->set(HeaderCommentFixer::class)
        ->call('configure', [[
            'header' => "UrlRewrite Bundle for Contao Open Source CMS.\n\n@copyright  Copyright (c) $date, terminal42 gmbh\n@author     terminal42 <https://terminal42.ch>\n@license    MIT",
        ]])
    ;
};
