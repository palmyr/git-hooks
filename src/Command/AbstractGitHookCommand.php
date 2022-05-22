<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;

abstract class AbstractGitHookCommand extends Command
{

    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        string $name
    )
    {
        $this->logger = $logger;
        parent::__construct('git:' . $name);
    }

    protected function configure()
    {
        parent::configure();

        $this->addArgument('branch', InputArgument::REQUIRED, 'The current branch');
    }
}