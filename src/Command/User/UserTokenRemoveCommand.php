<?php

namespace App\Command\User;

use App\Symfony\Command\AbstractContainerAwareCommand;
use App\Entity\UserToken;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserTokenRemoveCommand extends AbstractContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:user:token:remove');
        $this->setDescription('Removes out of date tokens');
        //todo remove all option
        //todo remove by type argument
        //todo remove by user argument
    }

    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        $em           = $this->getContainer()->get('doctrine.orm.entity_manager');
        $tokenManager = $this->getContainer()->get('app.user.token_manager');

        $qb = $em->createQueryBuilder();
        $qb->delete(UserToken::class, 'ut');
        foreach ($tokenManager->getTypes() as $type) {
            $qb->orWhere($qb->expr()->andX('ut.type = '.$type, 'DATE_ADD(ut.dateCreated, '.$tokenManager->getTtlByType($type).", 'SECOND') < :now"));
        }
        $qb->setParameter('now', new \DateTime());
        $cc = $qb->getQuery()->getResult();

        $output->writeln('<info>Rows deleted: '.$cc.'</info>');
        $output->writeln('<info>Execution time: '.round(microtime(true) - $timeStart, 3).'s</info>');
    }

}
