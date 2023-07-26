<?php

namespace JSmart\Foundation\Http;

use ArrayObject;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Response extends SymfonyResponse
{
    /**
     * Create a new HTTP response.
     *
     * @param mixed $content
     * @param int $status
     * @param array $headers
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function __construct($content = '', int $status = 200, array $headers = [])
    {
        $this->headers = new ResponseHeaderBag($headers);

        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * Set the content on the response.
     *
     * @param mixed $content
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function setContent(mixed $content): static
    {
        if ($this->shouldBeJson($content)) {

            $this->headers->set('Content-Type', 'application/json', true);

            $content = $this->morphToJson($content);

            if (!$content) {
                throw new \InvalidArgumentException(json_last_error_msg());
            }
        }

        parent::setContent($content);

        return $this;
    }

    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param mixed $content
     * @return bool
     */
    protected function shouldBeJson(mixed $content): bool
    {
        return is_array($content) || $content instanceof ArrayObject || $content instanceof JsonSerializable;
    }

    /**
     * Morph the given content into JSON.
     *
     * @param mixed $content
     * @return string
     */
    protected function morphToJson(mixed $content): string
    {
        return json_encode($content);
    }
}
