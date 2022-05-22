<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PreCommitHookCommand extends AbstractGitHookCommand
{

    public function __construct(
        LoggerInterface $logger
    )
    {
        parent::__construct($logger,'pre_commit');
    }

    protected function executeHook(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info("Testing");
        return self::SUCCESS;
    }
}