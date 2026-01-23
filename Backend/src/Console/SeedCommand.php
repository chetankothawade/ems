<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Database\Seeder\DatabaseSeeder;

class SeedCommand extends Command
{
    public function __construct(private DatabaseSeeder $seeder)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:seed')
             ->setDescription('Seed database with demo data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Seeding database...</info>');

        $this->seeder->run();

        $output->writeln('<info>Done ✅</info>');

        return Command::SUCCESS;
    }
}
