<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'create-user',
    description: 'Add a short description for your command',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de l\'utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Email de l\'utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('name');
        $arg2 = $input->getArgument('email');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
        $io->writeln('Name: ' . $input->getArgument('name'));
        $io->writeln('Email: ' . $input->getArgument('email'));
        $io->writeln('Password: ' . $input->getArgument('password'));

        if ($this->userRepository->findOneBy(['email' => $input->getArgument('email')])) {
            $io->error('Cet utilisateur existe déjà');
            return Command::FAILURE;
        } else {
            $user = new User();
            $user->setRoles(['ROLE_ADMIN']);
            $user->setName($input->getArgument('name'));
            $user->setEmail($input->getArgument('email'));
            $user->setPassword($this->hasher->hashPassword($user, $input->getArgument('password')));
            $this->em->persist($user);
            $this->em->flush();
            $io->success('Utilisateur créé avec succès');
        }
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
