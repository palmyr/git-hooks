<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Manager;

interface HookManagerInterface
{

    public function getHooks(string $hook, string $branch): array;
}