<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class EntityExists extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Row was not found';

    /**
     * @todo объект EntityManager
     * @var string|null
     */
    public $em;

    /**
     * @var string
     */
    public $entityClass;

    /**
     * @var string
     */
    public $repositoryMethod = 'findOneBy';

    /**
     * @var string
     */
    public $field = 'id';

    /**
     * @var bool
     */
    public $notExistMode = false;

    /**
     * @var string
     */
    public $service = 'validator.exist';

    /**
     * @return array
     */
    public function getRequiredOptions()
    {
        return ['entityClass',];
    }

}
