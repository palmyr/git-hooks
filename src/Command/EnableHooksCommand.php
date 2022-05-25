<?php declare(strict_types=1);

namespace Palmyr\GitHooks\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class EnableHooksCommand extends Command
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
        parent::__construct('hooks:enable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);

        $io->title("Preparing the git directory...");

        if ( !$this->filesystem->exists(".git") ) {
            $io->warning("Could not find .git directory, run this command in the root directory.");
            return self::INVALID;
        }

        if ( $this->filesystem->exists(".git/hooks") ) {
            $io->comment("Removing existing hooks directory.");
            $this->filesystem->remove(".git/hooks");
        }

        $this->filesystem->symlink($this->rootDir . DIRECTORY_SEPARATOR . "hooks", ".git/hooks");
        $io->success("Created symbolic link to hooks directory.");

        if ( !$this->filesystem->exists("hooks.yaml") ) {
            $this->filesystem->copy($this->rootDir . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "hooks.yaml", "hooks.yaml");
        }

        return self::SUCCESS;
    }
}