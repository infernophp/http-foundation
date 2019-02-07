<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Exception;

class OutputAlreadySentException extends \Exception implements HttpFoundationExceptionInterface
{
    /**
     * @return \Inferno\HttpFoundation\Exception\OutputAlreadySentException
     */
    public static function forOutput(): OutputAlreadySentException
    {
        return new static('Output already sent.');
    }
}
