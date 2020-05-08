<?php

namespace Console\Command;

use Client\Repositories\UserRepository\UserRepository;
use Shared\Core\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserUnsetTopCommand extends Command
{
    protected static $defaultName = 'user:unset-top';

    protected function configure()
    {
        $this->addArgument('userId', InputArgument::REQUIRED, 'User ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = (int) $input->getArgument('userId');

        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $userRepository->unsetTop($userId);

        return 0;
    }
}
