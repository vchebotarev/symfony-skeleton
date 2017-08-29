<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EqualValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Equal) {
            throw new UnexpectedTypeException($constraint, Equal::class);
        }

        $fieldValue = $this->context->getRoot()->get($constraint->field)->getData();

        $equal = $value === $fieldValue; //todo проверить для разных типов

        if ($constraint->notEqualMode == $equal) {
            $this->context->buildViolation($constraint->message)
                ->setParameters([
                    '{{ value }}' => $this->formatValue($value),
                    '{{ field }}' => $this->formatValue($constraint->field),
                ])
                ->addViolation();
        }
    }

}
