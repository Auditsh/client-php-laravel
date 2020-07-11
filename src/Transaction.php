<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel;

/**
 * Class Transaction
 * @package AuditSh\ClientPHPLaravel\src
 */
final class Transaction
{

    private $payload = [];
    /**
     * @var string
     */
    private $transactionId;

    /**
     * Transaction constructor.
     * @param string $transactionId
     */
    public function __construct(string $transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @param $name
     * @param array $data
     */
    public function addContext($name, array $data) : Transaction
    {
        $this->payload[$name] = $data;

        return $this;
    }

    public function toArray() : array
    {
        return ['transaction_id' => $this->transactionId, 'payload' => $this->payload];
    }
}
