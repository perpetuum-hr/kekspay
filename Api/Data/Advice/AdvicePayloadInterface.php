<?php

namespace Perpetuum\KeksPay\Api\Data\Advice;

interface AdvicePayloadInterface
{
    const BILL_ID = 'bill_id';
    const KEKS_ID = 'keks_id';
    const TID = 'tid';
    const STORE = 'store';
    const AMOUNT = 'amount';
    const STATUS = 'status';
    const MESSAGE = 'message';

    public function getBillId(): string;

    public function getKeksId(): string;

    public function getTid(): string;

    public function getStore(): string;

    public function getAmount(): float;

    public function getStatus(): int;

    public function getMessage(): string;
}
