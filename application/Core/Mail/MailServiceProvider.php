<?php

namespace JSmart\Core\Mail;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

use JSmart\Core\Foundation\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('mailer', function () {
            return new Mailer($this->getTransport());
        });

        $this->app->bind('mail', function ($app) {
            return new Mail($app);
        });
    }

    /**
     * Get transport.
     *
     * @return Transport\TransportInterface
     */
    private function getTransport(): Transport\TransportInterface
    {
        return Transport::fromDsn('sendmail+smtp://default');
    }
}
