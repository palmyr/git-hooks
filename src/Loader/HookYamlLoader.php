<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Loader;

use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class HookYamlLoader extends FileLoader
{

    public function supports(mixed $resource, string $type = null)
    {
        if (!\is_string($resource)) {
            return false;
        }

        if (null === $type && \in_array(pathinfo($resource, \PATHINFO_EXTENSION), ['yaml'], true)) {
            return true;
        }

        return \in_array($type, ['yaml'], true);
    }

    public function load(mixed $resource, string $type = null)
    {
        $path = $this->locator->locate($resource);

        $this->container->fileExists($path);

        $configValues = Yaml::parse(file_get_contents($resource));

        $this->container->loadFromExtension('hook', $configValues);
    }
}