<?php

namespace Paysera\Bundle\MabaWebpackExtensionBundle;

use Paysera\Bundle\MabaWebpackExtensionBundle\DependencyInjection\CompilerPass\NameWebpackLoggerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PayseraMabaWebpackExtensionBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new NameWebpackLoggerCompilerPass());
    }
}
