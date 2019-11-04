<?php

namespace App\Symfony\Validator\Constraints;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EntityExistsValidator extends ConstraintValidator
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if (!empty($constraint->except) && in_array($value, $constraint->except)) {
            return;
        }

        if ($constraint->em) {
            $em = $this->managerRegistry->getManager($constraint->em);
            if (!$em) {
                throw new ConstraintDefinitionException(sprintf('Object manager "%s" does not exist.', $constraint->em));
            }
        } else {
            $em = $this->managerRegistry->getManagerForClass($constraint->entityClass);
            if (!$em) {
                throw new ConstraintDefinitionException(sprintf('Unable to find the object manager associated with an entity of class "%s".', $constraint->entityClass));
            }
        }

        $repository = $em->getRepository($constraint->entityClass);

        if ($constraint->field !== null) {
            $criteria = [
                $constraint->field => $value,
            ];
        } else {
            $criteria = $value;
        }

        $result = $repository->{$constraint->repositoryMethod}($criteria);

        if ($constraint->notExistMode == !empty($result)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }

}
