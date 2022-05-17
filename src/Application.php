<?php declare(strict_types=1);

namespace Palmyr\GitHooks;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application AS BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class Application extends BaseApplication
{

    protected string $rootDir;

    protected ContainerInterface $container;

    protected function __construct()
    {
        parent::__construct('git-hook', '1.0.0');
        $this->rootDir = dirname(__DIR__, 1);
    }

    static public function init(): void
    {
        $application = new static();

        $application->container = $containerBuilder = new ContainerBuilder();

        $containerBuilder->set('container', $containerBuilder);
        $containerBuilder->set('application', $application);
        $containerBuilder->setParameter('root_dir', $application->rootDir);

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));

        $application->boot($loader);

        $commands = $containerBuilder->findTaggedServiceIds("command");

        foreach ($commands as $id => $tags) {
            /** @var Command $command */
            $command = $containerBuilder->get($id);
            $application->add($command);
        }

        $containerBuilder->compile(true);

        $application->run();
    }

    protected function boot(YamlFileLoader $loader): void
    {
        $loader->load($this->rootDir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.yaml');
    }
}