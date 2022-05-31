<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Service;

interface HookServiceInterface
{

    public function execute(string $hook, string $branch, array $arguments = []): bool;
}