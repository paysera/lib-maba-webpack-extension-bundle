<?php

namespace Paysera\Bundle\MabaWebpackExtensionBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PayseraMabaWebpackExtensionExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('paysera_maba_webpack_extension.replace_paths', $config['replace_paths']);
        $container->setParameter('paysera_maba_webpack_extension.replace_items', $config['replace_items']);
        $container->setParameter('paysera_maba_webpack_extension.replaced_webpack_config_path', $config['replaced_webpack_config_path']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
