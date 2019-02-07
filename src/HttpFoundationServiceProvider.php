<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Inferno\HttpFoundation\Emitter\ResponseEmitter;
use Inferno\HttpFoundation\Kernel\HttpKernel;

class HttpFoundationServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Pimple\Container $pimple
     *
     * @return void
     */
    public function register(Container $pimple): void
    {
        $this->provideHttpKernel($pimple);
    }

    /**
     * @param \Pimple\Container $container
     *
     * @return void
     */
    protected function provideHttpKernel(Container $container): void
    {
        $container->offsetSet(HttpKernel::class, function(Container $container) {
            $middlewareRunnerContainerKey = $container->offsetGet('config.middleware-runner');

            $errorResponseFactory = null;
            if ($container->offsetExists('error-response-factory')) {
                $errorResponseFactory = $container->offsetGet('error-response-factory');
            }

            return new HttpKernel(
                new ResponseEmitter(),
                $container->offsetGet($middlewareRunnerContainerKey),
                $container->offsetGet('server-request-factory'),
                $errorResponseFactory
            );
        });
    }
}
