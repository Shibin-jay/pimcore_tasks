<?php

namespace App\Command;

use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class customCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->setName('custom:task')
            ->setDescription('custom command created for custom tasks');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // dump
        $this->dump("Isn't that awesome?");

        // add newlines through flags
        $this->dump("Dump #2");

        // only dump in verbose mode
        $this->dumpVerbose("Dump verbose");

        // Output as white text on red background.
        $this->writeError('oh noes!');

        // Output as green text.
        $this->writeInfo('info');

        // Output as blue text.
        $this->writeComment('comment');

        // Output as yellow text.
        $this->writeQuestion('question');
        return Command::SUCCESS;
    }


}
