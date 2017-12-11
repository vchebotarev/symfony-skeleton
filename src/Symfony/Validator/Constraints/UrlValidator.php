<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UrlValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Url) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Url');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        if ('' === $value) {
            return;
        }

        /**
         * Мы можем быть не так жестоки, но тогда parse_url ведет себя не так как ожидается.
         * (для 'test.ru/123.php?a=2' host пуст, а path содержит 'test.ru/123.php' )
         */
        if (!filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter( '{{ value }}', $this->formatValue($value))
                ->addViolation();
            return;
        }

        $parsedUrl = parse_url($value);

        $check = [
            'scheme',
            'host',
            'port',
            'user',
            'pass',
            'path',
            'query',
            'fragment',
        ];

        foreach ($check as $c) {
            $valid  = true;
            $v      = isset($parsedUrl[$c]) ? $parsedUrl[$c] : null;
            $checkV = $constraint->{$c};
            if ($checkV !== null) {
                if (is_array($checkV)) {
                    if (empty($checkV) && $v !== null) {
                        $valid = false;
                    } elseif (!in_array($v, $checkV)) {
                        $valid = false;
                    }
                } else {
                    if (!preg_match($checkV, $v)) {
                        $valid = false;
                    }
                }
            }
            if (!$valid) {
                if ($constraint->messages[$c]) {
                    $message      = $constraint->messages[$c];
                    $invalidValue = $v;
                } else {
                    $message      = $constraint->message;
                    $invalidValue = $value;
                }
                $this->context->buildViolation($message)
                    ->setParameter( '{{ value }}', $this->formatValue($invalidValue))
                    ->addViolation();
                return;
            }
        }

        //todo check dns

        //todo check code

    }
}
