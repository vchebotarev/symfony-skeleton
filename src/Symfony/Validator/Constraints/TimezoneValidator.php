<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TimezoneValidator extends ConstraintValidator
{
    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Timezone) {
            throw new UnexpectedTypeException($constraint, Timezone::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($value instanceof \DateTimeZone) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $stringValue = (string) $value;

        $timezoneList = \DateTimeZone::listIdentifiers($constraint->what, $constraint->country);

        if (!in_array($stringValue, $timezoneList)) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('{{ timezone }}', $stringValue)
                    ->setInvalidValue($stringValue)
                    ->addViolation();
            }
        }
    }
}
