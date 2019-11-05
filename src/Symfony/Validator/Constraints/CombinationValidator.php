<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CombinationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Combination) {
            throw new UnexpectedTypeException($constraint, Combination::class);
        }

        $context = $this->context;

        $validator = $context->getValidator()->inContext($context);

        $violationCountPrevious = $context->getViolations()->count();

        foreach ($constraint->constraints as $constraintItem) {
            $validator->validate($value, $constraintItem);
            if ($constraint->breakOnFailure && $violationCountPrevious != $context->getViolations()->count()) {
                return;
            }
        }
    }
}
