<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->logger->info(sprintf(
            "Executing hook \"%s\" on branch \"%s\"",
            $input->getArgument("command"),
            $input->getArgument("branch")
        ));
    }
}
