<?php

namespace Pitech\MailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pitech_mailer');

        $rootNode
            ->children()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->example('app.mailer.my_mailer')
                            ->defaultValue('pitech_mailer.mailer.swift')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('logger')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->example('app.logger.my_logger')
                            ->defaultValue('pitech_mailer.logger.monolog')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('provider')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->example('app.provider.my_provider')
                            ->defaultValue('pitech_mailer.provider.yaml')
                        ->end()
                        ->scalarNode('file')
                            ->example('%kernel.root_dir%/config/emails.yml')
                            ->defaultValue('%kernel.root_dir%/config/emails.yml')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('parser')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->example('app.parser.my_parser')
                            ->defaultValue('pitech_mailer.parser.yaml')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templating')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('engine')
                            ->example('app.templating.my_template_engine')
                            ->defaultValue('pitech_mailer.templating.twig')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('translation_domain')
                    ->example('my_translation_domain')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
