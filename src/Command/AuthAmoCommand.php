<?php

namespace App\Command;

use AmoCRM\OAuth2\Client\Provider\AmoCRMException;
use App\Manager\AmoCRM\AmoCRMManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthAmoCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'auth:amo';

    protected function configure()
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'AmoCRM code');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var AmoCRMManager $amoManager */
        $amoManager = $this->container->get('app.manager.amo_crm_manager');
        $code = (string)$input->getArgument('code');
        $amoManager->setCode($code);
        try {
            $amoManager->createClient();
        } catch (AmoCRMException $exception) {
            $output->writeln($exception->getMessage());

            return 0;
        }

        $owner = $amoManager->getOwner();

        $output->writeln(printf('Hello, %s!', $owner->getName()));

        return 1;
    }
}
