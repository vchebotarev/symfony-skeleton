<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CountVariantsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CountVariants) {
            throw new UnexpectedTypeException($constraint, CountVariants::class);
        }

        if (null === $value) {
            return;
        }

        if (!\is_array($value) && !$value instanceof \Countable) {
            throw new UnexpectedTypeException($value, 'array or \Countable');
        }

        $count = \count($value);

        if (!\in_array($count, $constraint->variants)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ count }}', $count)
                ->setParameter('{{ variants }}', $this->formatValues($constraint->variants))
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setInvalidValue($value)
                ->addViolation();
        }
    }
}
