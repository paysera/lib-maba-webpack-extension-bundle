<?php

namespace Paysera\Bundle\MabaWebpackExtensionBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NameWebpackLoggerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('maba_webpack.command.compile')->addTag('monolog.logger', ['channel' => 'webpack']);
    }
}
