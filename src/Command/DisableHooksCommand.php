<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class DisableHooksCommand extends Command
{

    protected Filesystem $filesystem;

    protected string $rootDir;

    public function __construct(
        Filesystem $filesystem,
        string $rootDir
    )
    {
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
        parent::__construct('hooks:disable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Disabling hooks...");

        if ( $this->filesystem->exists(".git/hooks") ) {
            $this->filesystem->remove(".git/hooks");
            $io->success("Disabled hooks");
        } else {
            $io->warning("No hooks directory found");
        }

        return self::SUCCESS;
    }
}