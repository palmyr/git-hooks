<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Service;

use Palmyr\CommonUtils\Shell\Factory\ShellCommandFactory;
use Palmyr\GitHooks\Manager\HookManagerInterface;
use Psr\Log\LoggerInterface;

class HookService implements HookServiceInterface
{

    protected HookManagerInterface $hookManager;

    protected ShellCommandFactory $shellCommandFactory;

    protected LoggerInterface $logger;

    public function __construct(
        HookManagerInterface $hookManager,
        ShellCommandFactory $shellCommandFactory,
        LoggerInterface $logger,
    )
    {
        $this->hookManager = $hookManager;
        $this->shellCommandFactory = $shellCommandFactory;
        $this->logger = $logger;
    }

    public function execute(string $hook, string $branch, array $arguments = []): bool
    {
        $hasError = false;
        $hooks = $this->hookManager->getHooks($hook, $branch);

        $this->logger->debug(sprintf(
            "Executing hook [Hook: %s ] [Branch: %s ]",
            $hook,
            $branch
        ));
        foreach ( $hooks as $hooksName => $hookCommands ) {
            $this->logger->info(sprintf("Executing hook command [Hook: %s ] [Name %s ]", $hook, $hooksName));
            foreach ( $hookCommands as $command ) {
                if ( !$this->executeHook($command, $arguments) ) {
                    $hasError = true;
                }
            }
            $this->logger->info(sprintf("Executed hook command [Hook: %s ] [Name %s ]", $hook, $hooksName));
        }
        $this->logger->debug(sprintf(
            "Executed hook [ %s ] on branch [ %s ]",
            $hook,
            $branch
        ));

        return !$hasError;
    }

    protected function executeHook(string $hook, array $arguments): bool
    {
        $command = $this->shellCommandFactory->build($hook, $arguments);
        $this->logger->info($command->toString(), ["arguments" => $arguments]);
        $result = $command->execute();

        if ( $result->getCode() === 0 ) {
            return true;
        }

        $this->logger->info($result->toString());

        return false;
    }

}