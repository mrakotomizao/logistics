<?php
namespace AuthBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteClientCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sse:oauth:client:delete')
            ->setDescription('Delete the client whose name is passed as a parameter')
            ->addArgument(
                'client-name',
                InputArgument::REQUIRED,
                "Defines client name to be deleted.",
                null
                );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $clientName = $input->getArgument('client-name');
        $client = $clientManager->findClientBy(array("name" => $clientName));
        $clientManager->deleteClient($client);
        $output->writeln(
            sprintf(
                'Deleted client with name <info>%s</info>',
                $client->getName()
            )
        );
    }
}