<?php declare(strict_types=1);

namespace AuditSh\ClientPHPLaravel;

use AuditSh\ClientPHPLaravel\Transport\NullLoggerTransporter;
use AuditSh\ClientPHPLaravel\Transport\UDPTransporter;
use Illuminate\Support\ServiceProvider;

/**
 * Class LiteAPMLaravelServiceProvider
 * @package AuditSh\ClientPHPLaravel
 */
final class AuditShLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /**
         * Publish the config file
         */
        $this->publishes([__DIR__ . '/../config/audit.php' => config_path('audit.php')]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/audit.php', 'audit');

        $transporter = $this->buildTransporter();
        $this->app->singleton(
            'audit',
            function () use ($transporter) {


                return new AuditSh($transporter);
            }
        );
    }

    private function buildTransporter()
    {
        switch (config('audit.transporter')) {
            case 'udp':
                return new UDPTransporter(
                    config('audit.connection_host'),
                    config('audit.connection_port'),
                    config('audit.connection_key', ''),
                    config('audit.app_id', ''),
                    config('audit.stage')
                );
            case 'log':
                return new NullLoggerTransporter();
            default:
                return $this->app->make(config('audit.transporter'));
        }
    }
}
