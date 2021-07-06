<?php

declare(strict_types=1);

namespace winwin\mapper;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class GenerateBuilderCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('generate:builder');
        $this->addArgument('path', InputArgument::REQUIRED, '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $projectPath = getcwd();
        $autoloadFile = $projectPath.'/vendor/autoload.php';
        if (!file_exists($autoloadFile)) {
            $output->writeln('<error>vendor/autoload.php not found</error>');

            return -1;
        }
        /** @var ClassLoader $loader */
        $loader = require $autoloadFile;
        $loader->unregister();
        $loader->register(false);

        $generator = new BuilderGenerator(AnnotationReader::getInstance());
        $generator->setLogger(new ConsoleLogger($output));
        if (file_exists($projectPath.'/.generator-config')) {
            require $projectPath.'/.-config';
        }
        $path = $input->getArgument('path');
        if (is_file($path)) {
            $this->generate($generator, $path);
        } else {
            $finder = new Finder();
            $finder
                ->ignoreVCS(true)
                ->name('*.php')
                ->notPath('vendor')
                ->in($path);
            foreach ($finder as $file) {
                $output->writeln("<info>process {$file->getPathname()}</info>");
                $this->generate($generator, $file->getPathname());
            }
        }

        return 0;
    }

    private function generate(BuilderGenerator $generator, string $path): void
    {
        $result = $generator->generate($path);
        if (null !== $result) {
            $result->replace();
        }
    }
}
