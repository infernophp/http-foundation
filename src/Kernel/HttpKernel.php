<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Kernel;

use Inferno\HttpFoundation\Emitter\ResponseEmitter;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class HttpKernel implements HttpKernelInterface
{
    /**
     * @var \Inferno\HttpFoundation\Emitter\ResponseEmitter
     */
    protected $responseEmitter;

    /**
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * @var callable
     */
    protected $requestFactory;

    /**
     * @var callable|null
     */
    protected $errorResponseFactory;

    /**
     * @param \Inferno\HttpFoundation\Emitter\ResponseEmitter $responseEmitter
     * @param \Psr\Http\Server\RequestHandlerInterface $requestHandler
     * @param callable $requestFactory
     * @param callable|null $errorResponseFactory
     */
    public function __construct(
        ResponseEmitter $responseEmitter,
        RequestHandlerInterface $requestHandler,
        callable $requestFactory,
        ?callable $errorResponseFactory = null
    ) {
        $this->responseEmitter = $responseEmitter;
        $this->requestHandler = $requestHandler;
        $this->requestFactory = $requestFactory;
        $this->errorResponseFactory = $errorResponseFactory;
    }

    /**
     * @throws \Throwable
     * @throws \Inferno\HttpFoundation\Exception\HeaderAlreadySentException
     * @throws \Inferno\HttpFoundation\Exception\OutputAlreadySentException
     *
     * @return void
     */
    public function __invoke(): void
    {
        try {
            $request = ($this->requestFactory)();
            $response = $this->requestHandler->handle($request);
        } catch (Throwable $throwable) {
            if ($this->errorResponseFactory === null) {
                throw $throwable;
            }

            $response = ($this->errorResponseFactory)($throwable, $request);
        }

        $this->responseEmitter->emit($response);
    }
}
