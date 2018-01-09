<?php

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use VkBrackets\BracketsChecker;

class CheckFileCommand extends Command
{
    protected function configure()
    {
       $this
           ->setName('check:file')
           ->setDescription('Checking file for brackets')
           ->addArgument('file', InputArgument::REQUIRED, 'Set path to file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');
        // Проверяем существоание файла
        if (!file_exists($file)) {
            $io->error("File '$file' not found!");
            die();
        }
        $str = file_get_contents($file);
        // Проверяем скобки при помощи BracketsChecker
        try {
            $checker = new BracketsChecker($str);
        } catch (\InvalidArgumentException $e) {
            $io->warning($e->getMessage());
            die();
        }
        if ($checker->result) {
            $io->success('File correct');
        } else {
            $io->warning('File incorrect: '.$checker->error);
        }
    }
}