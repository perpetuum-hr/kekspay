<?php

namespace Perpetuum\KeksPay\Api\Data;

/**
 * container for payment status codes
 */
class PaymentStatusCode
{
    public const OK = 0;
    public const FAILED = -6;
    public const PAID_MESSAGE = 'Paid';
    public const ABORT = 1;
    public const ABORT_MESSAGE = 'Aborted';
    public const FAILED_MESSAGE = 'Failed';
}
