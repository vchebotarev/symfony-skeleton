<?php

namespace App\Command\User\AuthLog;

use App\Entity\UserAuthLog;
use App\Symfony\Command\AbstractContainerAwareCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserAuthLogRemoveCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:auth-log:remove');
        $this->setDescription('Delete old user auth logs');
    }

    /**
     * @inheritDoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        $expr = $em->getExpressionBuilder();

        $rowCount = $em->createQueryBuilder()
            ->delete(UserAuthLog::class, 'l')
            ->andWhere($expr->lt('l.dateCreated', ':dt'))
            ->setParameter('dt', (new \DateTime('- 1 year'))->format('Y-m-d'))
            ->getQuery()
            ->execute();

        $output->writeln('<info>Rows removed from `user_auth_log`: ' . $rowCount . '</info>');
        $output->writeln('<info>Execution time: '.round(microtime(true) - $timeStart, 3).'s</info>');
    }

}
