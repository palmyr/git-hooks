<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Palmyr\GitHooks\Service\HookServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractGitHookCommand extends Command
{

    protected HookServiceInterface $hookService;

    protected LoggerInterface $logger;

    public function __construct(
        HookServiceInterface $hookService,
        LoggerInterface $logger,
        string $name
    )
    {
        $this->hookService = $hookService;
        $this->logger = $logger;
        parent::__construct('git:' . $name);
    }

    protected function configure()
    {
        parent::configure();

        $this->addArgument('branch', InputArgument::REQUIRED, 'The current branch');
    }
}
