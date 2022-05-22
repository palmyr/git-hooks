<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultGItHookCommand extends AbstractGitHookCommand
{

    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger, 'default');
    }

    protected function configure()
    {
        parent::configure();
        $this->addArgument('hook', InputArgument::REQUIRED, 'The name of the hook');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info($input->getArgument("branch"));
        return self::SUCCESS;
    }
}