<?php

namespace JSmart\Mail;

class Mailable
{
    /**
     * The "to" recipients of the message.
     *
     * @var array
     */
    protected array $to = [];

    /**
     * The "cc" recipients of the message.
     *
     * @var array
     */
    protected array $cc = [];

    /**
     * The "bcc" recipients of the message.
     *
     * @var array
     */
    protected array $bcc = [];

    /**
     * The "from" recipients of the message.
     *
     * @var array
     */
    protected array $from = [];

    /**
     * The "reply to" recipients of the message.
     *
     * @var array
     */
    protected array $replyTo = [];

    /**
     * The subject of the message.
     *
     * @var string
     */
    protected string $subject;

    /**
     * The message priority level.
     *
     * @var int
     */
    protected int $priority;

    /**
     * The plain text view to use for the message.
     *
     * @var string
     */
    protected string $text;
    protected string $textCharset;

    /**
     * The HTML to use for the message.
     *
     * @var string
     */
    protected string $html;
    protected string $htmlCharset = '';

    /**
     * Set the recipients of the message.
     *
     * @param array|string $address
     * @return $this
     */
    public function to(array|string $address): static
    {
        $this->to = array_merge($this->to, $this->makeAddress($address));

        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param array|string $address
     * @return $this
     */
    public function cc(array|string $address): static
    {
        $this->cc = array_merge($this->cc, $this->makeAddress($address));

        return $this;
    }

    /**
     * Set the recipients of the message.
     *
     * @param array|string $address
     * @return $this
     */
    public function bcc(array|string $address): static
    {
        $this->bcc = array_merge($this->bcc, $this->makeAddress($address));

        return $this;
    }

    /**
     * Add a "from" address to the message.
     *
     * @param string $address
     * @param string|null $name
     * @return $this
     */
    public function from(string $address, string $name = null): static
    {
        $this->from = [
            'address'   => $address,
            'name'      => $name,
        ];

        return $this;
    }

    /**
     * Add a "reply to" address to the message.
     *
     * @param array|string $address
     * @return $this
     */
    public function replyTo(array|string $address): static
    {
        $this->replyTo = array_merge($this->replyTo, $this->makeAddress($address));

        return $this;
    }

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     * @return $this
     */
    public function subject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the message priority level.
     *
     * @param int $level
     * @return $this
     */
    public function priority(int $level): static
    {
        $this->priority = $level;

        return $this;
    }

    /**
     * Set the plain text view for the message.
     *
     * @param $body
     * @param string $charset
     * @return $this
     */
    public function text($body, string $charset = 'utf-8'): static
    {
        $this->text = $body;
        $this->textCharset = $charset;

        return $this;
    }

    /**
     * Set the rendered HTML content for the message.
     *
     * @param $body
     * @param string $charset
     * @return $this
     */
    public function html($body, string $charset = 'utf-8'): static
    {
        $this->html = $body;
        $this->htmlCharset = $charset;

        return $this;
    }

    /**
     * Make address as array.
     *
     * @param array|string $address
     * @return array
     */
    private function makeAddress(array|string $address): array
    {
        if (is_string($address)) {
            $address = explode(',', $address);
        }

        return $address;
    }
}
