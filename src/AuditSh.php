<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel;

use AuditSh\ClientPHPLaravel\Transport\Transport;
use Ramsey\Uuid\Uuid;

/**
 * Class AuditSh
 * @package AuditSh\ClientPHPLaravel
 */
final class AuditSh
{
    private const VERSION = '0.0';
    /**
     * @var Transaction|null
     */
    private $currentTransaction;
    /**
     * @var Transport
     */
    private $transport;

    /**
     * AuditSh constructor.
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
        register_shutdown_function([$this, '__destruct']);
    }

    public function startTransaction() : void
    {
        $this->currentTransaction = new Transaction(Uuid::uuid4()->toString());
        $this->currentTransaction->addContext('internal', ['version' => self::VERSION, 'time' => time()]);
    }

    public function currentTransaction() : ?Transaction
    {
        return $this->currentTransaction;
    }

    /**
     * Flush data to the remote platform.
     *
     * @throws \Exception
     */
    public function __destruct()
    {

        $this->transport->sendPayload($this->currentTransaction);

        unset($this->currentTransaction);
    }
}
