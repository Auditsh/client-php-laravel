<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel\Facade;

use AuditSh\ClientPHPLaravel\Transaction;
use Illuminate\Support\Facades\Facade;

/**
 * Class AuditSh
 * @package AuditSh\ClientPHPLaravel\Facade
 * @method static startTransaction()
 * @method static Transaction currentTransaction()
 */
final class AuditSh extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'audit';
    }
}
