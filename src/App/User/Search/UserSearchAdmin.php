<?php

namespace App\User\Search;

use App\Search\Param;
use App\Entity\User;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

class UserSearchAdmin extends AbstractItemsSource
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(Entitymanager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @inheritdoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        $qb = $this->createAndModifyQb($options);
        $qb->select('u');

        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        if ($sort) {
            switch ($sort) {
                case Param::CREATED:
                    $qb->addOrderBy('u.dateCreated', $sortOrder);
                    break;
                case Param::LOGIN:
                    //todo
                    break;
                case Param::USERNAME:
                    $qb->addOrderBy('u.username', $sortOrder);
                    break;
                case Param::EMAIL:
                    $qb->addOrderBy('u.email', $sortOrder);
                    break;
            }
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array|object $options
     * @return QueryBuilder
     */
    protected function createAndModifyQb($options)
    {
        $qb = $this->em->getRepository(User::class)->createQueryBuilder('u');

        if (isset($options[Param::Q]) && $options[Param::Q]) {
            $q = $options[Param::Q];

            $orX = [$qb->expr()->like('u.email', ':q')];
            if (strpos($q, '@') === false) {
                $orX[] = $qb->expr()->like('u.username', ':q');
            }
            if (filter_var($q, FILTER_VALIDATE_INT)) {
                $orX[] = $qb->expr()->eq('u.id', ':q_int');
                $qb->setParameter('q_int', $q);
            }

            $qb->andWhere(new Orx($orX));
            $qb->setParameter('q', '%'.$q.'%');
        }

        if (isset($options[Param::ENABLED]) && $options[Param::ENABLED]) {
            $enabled = $options[Param::ENABLED];
            $qb->andWhere($qb->expr()->eq('u.isEnabled', ':enabled'));
            $qb->setParameter('enabled', boolval($enabled));
        }

        if (isset($options[Param::LOCKED]) && $options[Param::LOCKED]) {
            $locked = $options[Param::LOCKED];
            $qb->andWhere($qb->expr()->eq('u.isLocked', ':locked'));
            $qb->setParameter('locked', boolval($locked));
        }

        if (isset($options[Param::CREATED]) && $options[Param::CREATED]) {
            $dateCreated = $options[Param::CREATED];
            $from        = isset($dateCreated[Param::FROM]) && $dateCreated[Param::FROM] ? $dateCreated[Param::FROM] : null;
            $to          = isset($dateCreated[Param::TO]) && $dateCreated[Param::TO] ? $dateCreated[Param::TO] : null;
            if ($from) {
                $qb->andWhere($qb->expr()->gte('u.dateCreated', ':created_from'));
                $qb->setParameter('created_from', $from);
            }
            if ($to) {
                $qb->andWhere($qb->expr()->lte('u.dateCreated', ':created_to'));
                $qb->setParameter('created_to', $to);
            }
        }

        if (isset($options[Param::LOGIN]) && $options[Param::LOGIN]) {
            $dateLogin = $options[Param::LOGIN];
            $from        = isset($dateLogin[Param::FROM]) && $dateLogin[Param::FROM] ? $dateLogin[Param::FROM] : null;
            $to          = isset($dateLogin[Param::TO]) && $dateLogin[Param::TO] ? $dateLogin[Param::TO] : null;
            if ($from) {
                //todo
            }
            if ($to) {
                //todo
            }
        }

        return $qb;
    }

    /**
     * @inheritdoc
     */
    protected function getTotalCount($options = [])
    {
        $qb = $this->createAndModifyQb($options);
        $qb->select($qb->expr()->count('u.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }

}
