<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel\Transport;

use AuditSh\ClientPHPLaravel\Transaction;
use ParagonIE\EasyRSA\EasyRSA;
use ParagonIE\EasyRSA\PublicKey;

/**
 * Class UDPTransporter
 * @package AuditSh\ClientPHPLaravel\Transport
 */
final class UDPTransporter implements Transport
{
    /**
     * @var string
     */
    private $stage;
    /**
     * @var string
     */
    private $host;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $appId;

    /**
     * NullLoggerTransporter constructor.
     * @param string $host
     * @param int $port
     * @param string $token
     * @param string $stage
     */
    public function __construct(string $host, int $port, string $publicKey, string $appId, string $stage)
    {
        $this->host = $host;
        $this->port = $port;
        $this->key = $publicKey;
        $this->appId = $appId;
        $this->stage = $stage;
    }

    public function sendPayload(Transaction $transaction) : void
    {
        $publicKey = new PublicKey(base64_decode($this->key));

        $securePayload = json_encode(['payload' => $transaction->toArray(), 'stage' => $this->stage]);
        $payload = json_encode(
            [
                'secure' => EasyRSA::encrypt($securePayload, $publicKey),
                'app_id' => $this->appId,
            ]
        );
        $this->socketPush($payload);
        logger('message pushed', [$payload]);
    }

    /**
     * @param string $payload
     */
    private function socketPush(string $payload) : void
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_sendto(
            $socket,
            $payload,
            strlen($payload),
            0,
            $this->host,
            $this->port
        );
    }
}
