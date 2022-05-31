<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Manager;

class HookManager implements HookManagerInterface
{

    protected array $hookConfig;

    public function __construct(
        array $hookConfig
    )
    {
        $this->hookConfig = $hookConfig;
    }

    public function getHooks(string $hook, string $branch): array
    {
        $allowedHooks = [];
        if ( array_key_exists($hook, $this->hookConfig) ) {
                foreach ( $this->hookConfig[$hook] as $hookCommandName => $hookCommands ) {
                    $branches =  $hookCommands["branches"];
                    if ( in_array($branch, $branches) || in_array("*", $branches) ) {
                        $allowedHooks[$hookCommandName] = $hookCommands["commands"];
                    }
                }

        }
        return $allowedHooks;
    }
}