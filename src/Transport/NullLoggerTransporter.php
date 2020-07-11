<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel\Transport;

use AuditSh\ClientPHPLaravel\Transaction;

/**
 * Class NullLoggerTransporter
 * @package AuditSh\ClientPHPLaravel\Transport
 */
final class NullLoggerTransporter implements Transport
{
    public function sendPayload(Transaction $transaction) : void
    {
        logger('Audit Payload', ['payload' => $transaction->toArray()]);
    }
}
