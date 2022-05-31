<?php declare(strict_types=1);

namespace Palmyr\GitHooks\DependencyInjection;

use Monolog\Handler\StreamHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension AS BaseExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class Extension extends BaseExtension
{

    public function load(array $configs, ContainerBuilder $container): void
    {

        $rootDirectory = $container->getParameter("root_directory");
        $loader = new YamlFileLoader($container, new FileLocator($rootDirectory . DIRECTORY_SEPARATOR . "config"));

        $loader->load("hooks.yaml");


        $config = $this->processConfiguration(new Configuration(), $configs);

        if ( array_key_exists("log_file", $config) ) {
            $this->registerLogService($container, $config["log_file"]);
        }

        if ( array_key_exists("hooks", $config) ) {
            $this->registerHookConfiguration($container, $config["hooks"]);
        }
    }

    public function getAlias(): string
    {
        return 'hook';
    }

    protected function registerLogService(ContainerBuilder $container, string $logFile): void
    {
        $monoLoggerDefinition = $container->getDefinition('monolog.logger');

        $handlerDefinition = new Definition(StreamHandler::class, [
            $logFile,
            "debug",
        ]);

        $container->setDefinition("monolog.handler.file", $handlerDefinition);

        $monoLoggerDefinition->addMethodCall("pushHandler", [new Reference("monolog.handler.file")]);
    }

    protected function registerHookConfiguration(ContainerBuilder $container, array $hookConfig): void
    {
        $definition = $container->getDefinition("hooks.manager.hooks");

        $definition->setArgument("\$hookConfig", $hookConfig);


    }
}