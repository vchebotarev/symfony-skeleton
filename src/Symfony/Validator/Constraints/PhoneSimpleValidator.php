<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneSimpleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneSimple) {
            throw new UnexpectedTypeException($constraint, PhoneSimple::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $stringValue = (string) $value;

        $masks = $constraint->mask;

        if (!is_array($masks)) {
            $masks = [$masks];
        }

        $patternList = array_map(function($mask){
            return str_replace('#', '\d', $mask);
        }, $masks);

        $pattern = '/^((' . implode(')|(', $patternList) . '))$/';

        if (!preg_match($pattern, $stringValue)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setInvalidValue($value)
                ->addViolation();
        }
    }
}
