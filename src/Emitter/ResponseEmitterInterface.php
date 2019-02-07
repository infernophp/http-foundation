<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Emitter;

use Psr\Http\Message\ResponseInterface;

interface ResponseEmitterInterface
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     */
    public function emit(ResponseInterface $response) : void;
}
