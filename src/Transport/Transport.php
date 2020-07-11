<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel\Transport;

use AuditSh\ClientPHPLaravel\Transaction;

/**
 * Interface Transport
 * @package AuditSh\ClientPHPLaravel\Transport
 */
interface Transport
{
    public function sendPayload(Transaction $transaction) : void;
}
