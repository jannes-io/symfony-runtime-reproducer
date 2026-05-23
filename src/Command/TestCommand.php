<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('app:test')]
class TestCommand
{
    public function __invoke(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('hello');
        return Command::SUCCESS;
    }
}
