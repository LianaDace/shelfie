<?php

namespace App\Command;

use App\Factory\BookFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'app:seed-books',
    description: 'Adds random books to the database',
)]
class SeedBooksCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        BookFactory::new()->many(100)->create();
        $output->writeln('100 books are added to database');
        return Command::SUCCESS;
    }
}
