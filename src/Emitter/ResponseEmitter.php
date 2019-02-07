<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Emitter;

use Inferno\HttpFoundation\Exception\HeaderAlreadySentException;
use Inferno\HttpFoundation\Exception\OutputAlreadySentException;
use Psr\Http\Message\ResponseInterface;

class ResponseEmitter implements ResponseEmitterInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \Inferno\HttpFoundation\Exception\HeaderAlreadySentException
     * @throws \Inferno\HttpFoundation\Exception\OutputAlreadySentException
     *
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        $this->ensureNoPreviousOutput();
        $this->emitHeaders($response);
        $this->emitBody($response);
    }

    /**
     * @throws \Inferno\HttpFoundation\Exception\HeaderAlreadySentException
     * @throws \Inferno\HttpFoundation\Exception\OutputAlreadySentException
     *
     * @return void
     */
    protected function ensureNoPreviousOutput(): void
    {
        if (ob_get_level() > 0 && ob_get_length() > 0) {
            throw OutputAlreadySentException::forOutput();
        }

        if (headers_sent()) {
            throw HeaderAlreadySentException::forHeader();
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     */
    protected function emitHeaders(ResponseInterface $response) : void
    {
        $reasonPhrase = $response->getReasonPhrase();
        $statusCode   = $response->getStatusCode();

        $statusHeader = sprintf('HTTP/%s %d%s',
            $response->getProtocolVersion(),
            $statusCode,
            ($reasonPhrase ? ' ' . $reasonPhrase : '')
        );

        header($statusHeader, true, $statusCode);

        foreach ($response->getHeaders() as $header => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $header, $value), true, $statusCode);
            }
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     */
    protected function emitBody(ResponseInterface $response) : void
    {
        echo $response->getBody();
    }
}
