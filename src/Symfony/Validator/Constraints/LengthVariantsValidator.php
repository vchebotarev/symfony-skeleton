<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LengthVariantsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof LengthVariants) {
            throw new UnexpectedTypeException($constraint, LengthVariants::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $stringValue = (string) $value;

        if (!$invalidCharset = !@mb_check_encoding($stringValue, $constraint->charset)) {
            $length = mb_strlen($stringValue, $constraint->charset);
        }

        if ($invalidCharset) {
            $this->context->buildViolation($constraint->charsetMessage)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setParameter('{{ charset }}', $constraint->charset)
                ->setInvalidValue($value)
                ->addViolation();
            return;
        }

        if (!in_array($length, $constraint->variants)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($stringValue))
                ->setParameter('{{ length }}', $length)
                ->setParameter('{{ variants }}', $this->formatValues($constraint->variants))
                ->setInvalidValue($value)
                ->addViolation();
        }
    }

}
