<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class Md5Validator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Md5) {
            throw new UnexpectedTypeException($constraint, Md5::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (strlen($value) !== 32 || !ctype_xdigit($value)) {//or regexp '/^[a-f0-9]{32}$/i'
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
