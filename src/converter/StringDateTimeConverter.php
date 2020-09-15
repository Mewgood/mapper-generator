<?php

declare(strict_types=1);

namespace winwin\mapper\converter;

use Carbon\Carbon;
use kuiper\reflection\ReflectionTypeInterface;
use winwin\mapper\CastContext;

class StringDateTimeConverter implements Converter
{
    public function support(ReflectionTypeInterface $from, ReflectionTypeInterface $to): bool
    {
        return $to->isClass()
            && is_a($to->getName(), \DateTime::class, true)
            && 'string' === $from->getName();
    }

    public function convert(CastContext $context): string
    {
        if (class_exists(Carbon::class)) {
            if (null !== $context->getMapping()->dateFormat) {
                return sprintf('\Carbon\Carbon::parse(%s)', $context->getValue());
            } else {
                return sprintf('\Carbon\Carbon::createFromFormat(%s, %s)',
                    $context->getMapping()->dateFormat,
                    $context->getValue());
            }
        }

        return sprintf("\DateTime::createFromFormat(%s, %s)",
            var_export($context->getMapping()->dateFormat ?? 'Y-m-d H:i:s', true),
            $context->getValue());
    }
}
