<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Kernel;

interface HttpKernelInterface
{
    /**
     * @return void
     */
    public function __invoke(): void;
}
