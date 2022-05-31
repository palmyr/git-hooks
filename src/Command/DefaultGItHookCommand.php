<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Palmyr\GitHooks\Service\HookServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultGItHookCommand extends AbstractGitHookCommand
{

    public function __construct(
        HookServiceInterface $hookService,
        LoggerInterface $logger
    )
    {
        parent::__construct($hookService, $logger, 'default');
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('hook', InputArgument::REQUIRED, 'The name of the hook');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ( $this->hookService->execute($input->getArgument("hook"), $input->getArgument("branch")) ) {
            $this->logger->info("Hook executed successfully");
            return self::SUCCESS;
        } else {
            $this->logger->error("Hook executed with errors");
            return self::FAILURE;
        }

    }
}