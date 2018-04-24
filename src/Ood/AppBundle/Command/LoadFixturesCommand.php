<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LoadFixturesCommand
 *
 * @package Ood\AppBundle\Command
 */
class LoadFixturesCommand extends ContainerAwareCommand
{
    /** *******************************
     *   CONSTANT
     */

    const NAME = 'app:fixtures:load';

    /** *******************************
     *   METHODS
     */

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Purges database and loads all fixtures to database.')
            ->setHelp('This command allows ...');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln(['', '==================', 'Drop database', '']);
        $application->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:database:drop',
                    '--force' => true
                ]
            ),
            $output
        );

        $output->writeln(['', '==================', 'Create database', '']);
        $application->run(
            new ArrayInput(
                [
                    'command'         => 'doctrine:database:create',
                    '--if-not-exists' => true
                ]
            ),
            $output
        );

        $output->writeln(['', '==================', 'Update schema', '']);
        $application->run(
            new ArrayInput(
                [
                    'command' => 'doctrine:schema:update',
                    '--force' => true
                ]
            ),
            $output
        );

        $output->writeln(['', '==================', 'Load data', '']);
        $application->run(
            new ArrayInput(
                [
                    'command'          => 'doctrine:fixture:load',
                    '--no-interaction' => true
                ]
            ),
            $output
        );
    }
}
