<?php

namespace Mollie\Api\Types;

class MandateMethod
{
    const DIRECTDEBIT = "directdebit";
    const CREDITCARD = "creditcard";
    public static function getForFirstPaymentMethod($firstPaymentMethod)
    {
        if (\in_array($firstPaymentMethod, [\Mollie\Api\Types\PaymentMethod::APPLEPAY, \Mollie\Api\Types\PaymentMethod::CREDITCARD])) {
            return static::CREDITCARD;
        }
        return static::DIRECTDEBIT;
    }
}
