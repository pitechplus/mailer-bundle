<?php

namespace Pitech\MailerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PitechMailerExtension extends Extension implements PrependExtensionInterface
{
    const CONFIG_DIR = '/../Resources/config';
    const SERVICES_DIR = 'services';
    const PARAMS_DIR = 'parameters';
    const DEFAULT_PROVIDER_FILE = 'config/emails.yml';

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['PitechMailerBundle'])) {
            $container->prependExtensionConfig(
                'monolog',
                [
                    'channels' => ['pitech_mailer'],
                    'handlers' => [
                        'pitech_mailer' => [
                            'level' => 'debug',
                            'type' => 'stream',
                            'path' => '%kernel.logs_dir%/pitech_mailer.log',
                            'channels' => ['pitech_mailer']
                        ]
                    ]
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $configDir = sprintf('%s%s', __DIR__, self::CONFIG_DIR);
        $loader = new YamlFileLoader($container, new FileLocator($configDir));

        foreach ([self::SERVICES_DIR, self::PARAMS_DIR] as $dir) {
            $paths = scandir(sprintf('%s/%s', $configDir, $dir));
            foreach ($paths as $path) {
                if (is_file(sprintf('%s/%s/%s', $configDir, $dir, $path))) {
                    $loader->load(sprintf('%s/%s', $dir, $path));
                }
            }
        }

        $container
            ->getDefinition($config['mailer']['class'])
            ->addArgument($container->getDefinition($config['logger']['class']));

        if (!file_exists($config['provider']['file'])) {
            throw new \Exception(sprintf("File %s doesn't exist.", $config['provider']['file']));
        }

        $container
            ->getDefinition($config['provider']['class'])
            ->addArgument($config['provider']['file'])
            ->addArgument($container->getDefinition($config['parser']['class']))
            ->addArgument($container->getDefinition($config['templating']['engine']))
            ->addArgument($config['translation_domain']);

        $container
            ->getDefinition('pitech_mailer.resolver.mail')
            ->addArgument($container->getDefinition($config['mailer']['class']))
            ->addArgument($container->getDefinition($config['provider']['class']));
    }
}
