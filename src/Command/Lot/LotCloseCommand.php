<?php

namespace App\Command\Lot;

use App\Entity\Lot;
use App\Symfony\Command\AbstractContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LotCloseCommand extends AbstractContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('app:lot:close');
        $this->setDescription('Close lot by time');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $timeStart = microtime(true);

        $em         = $this->getContainer()->get('doctrine.orm.entity_manager');
        $lotManager = $this->getContainer()->get('app.lot.manager');
        $expr       = $em->getExpressionBuilder();

        $lots = $em
            ->createQueryBuilder()
            ->select('l')
            ->from(Lot::class, 'l')
            ->andWhere($expr->eq('l.status', Lot::STATUS_ACTIVE))
            ->andWhere($expr->gte('l.dateClosed', ':now'))
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();

        $progress = new ProgressBar($output, count($lots));

        foreach ($lots as $lot) {
            $lotManager->close($lot);
            $progress->advance();
        }

        $output->writeln('');
        $output->writeln('<info>Execution time: '.round(microtime(true) - $timeStart, 3).'s</info>');
    }

}
