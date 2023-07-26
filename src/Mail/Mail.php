<?php

namespace JSmart\Mail;

use JSmart\Foundation\Application;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class Mail
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    protected array $to = [];

    /**
     * Create a new Mail instance.
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Set the recipients of the message.
     *
     * @param array|string $address
     * @return $this
     */
    public function to(array|string $address): static
    {
        //

        return $this;
    }

    /**
     * Send the message using the given mailer.
     *
     * @param RawMessage|null $message
     * @return void
     */
    public function send(RawMessage $message = null): void
    {
        if (!$message instanceof RawMessage) {
            $message = new Email();
        }

        dump($message);

        //$this->app['mailer']->send($message);
    }
}
