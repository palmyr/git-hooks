<?php declare(strict_types=1);

namespace Palmyr\GitHooks;

use Palmyr\GitHooks\DependencyInjection\CompilerPass;
use Palmyr\GitHooks\DependencyInjection\Extension;
use Palmyr\GitHooks\Loader\HookYamlLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application AS BaseApplication;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Application extends BaseApplication
{

    protected string $rootDirectory;

    protected ContainerBuilder $container;

    protected function __construct()
    {
        parent::__construct('git-hook', '1.0.0');
        $this->rootDirectory = $this->getRootDirectory();
    }

    static public function init(): void
    {
        $application = new static();

        $application->container = $containerBuilder = new ContainerBuilder();

        $containerBuilder->set('container', $containerBuilder);
        $containerBuilder->set('application', $application);
        $containerBuilder->setParameter('root_directory', $application->rootDirectory);

        $application->boot();

        $containerBuilder->compile(true);

        $application->run();
    }

    protected function getRootDirectory(): string
    {
        return dirname(__DIR__, 1);
    }

    protected function boot(): void
    {
        $this->container->addCompilerPass(new CompilerPass());
        $this->container->registerExtension(new Extension());
        $fileLocator = new FileLocator();
        $this->loadServices(new YamlFileLoader($this->container, $fileLocator));
        $this->loadHooks(new HookYamlLoader($this->container, $fileLocator));
    }

    protected function loadServices(YamlFileLoader $loader): void
    {
        $loader->load($this->rootDirectory . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'services.yaml');
    }

    protected function loadHooks(HookYamlLoader $loader): void
    {
        $workingDirectory = getcwd();
        foreach (['hooks.yaml', 'hooks.local.yaml'] as $filename) {
            $filePath = $workingDirectory . DIRECTORY_SEPARATOR . $filename;
            if ( $this->container->fileExists($filePath) ) {
                $loader->load($filePath);
            }
        }
    }
}