<?php
namespace AuthBundle\Command;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sse:oauth:user:create')
            ->setDescription('Creates a new user')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Sets username for the user.',
                null
            )
            ->addArgument(
                'distributor',
                InputArgument::REQUIRED,
                'Sets the distributor to which the user belongs.',
                null
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Sets email for the user.',
                null
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Sets password for the user.',
                null
            )
            ->addArgument(
                'firstname',
                InputArgument::OPTIONAL,
                'Sets firstname for the user.',
                null
            )
            ->addArgument(
                'lastname',
                InputArgument::OPTIONAL,
                'Sets lastname for the user.',
                null
            )
            ->addOption(
                'role',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Sets role for user. use multiple time to set multiple roles.',
                array(UserInterface::ROLE_DEFAULT)
            )
            ->addOption(
                'inactive',
                null,
                InputOption::VALUE_NONE,
                'Set the user as inactive'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $username = $input->getArgument('username');
        $user->setUsername($username);
        $user->setCreatedDate(new \DateTime());
        $user->setEmail($input->getArgument('email'));
        $user->setPlainPassword($input->getArgument('password'));
        $user->setFirstname($input->getArgument('firstname'));
        $user->setLastname($input->getArgument('lastname'));
        $user->setRoles($input->getOption('role'));
        $user->setEnabled(!$input->getOption('inactive'));
        $distributorRepository = $this->getContainer()->get('doctrine')->getManager()->getRepository('LogisticsBundle:Distributor');
        $distributor = $distributorRepository->findOneBy(array('code' => $input->getArgument('distributor')));

        $user->setDistributor($distributor);

        $userManager->updateUser($user);


        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }
}

// php bin/console sse:oauth:user:create juliane juliane.duval@batiment.setec.fr test Juliane Duval