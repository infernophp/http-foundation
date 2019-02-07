<?php

declare(strict_types=1);

namespace Inferno\HttpFoundation\Exception;

class HeaderAlreadySentException extends \Exception implements HttpFoundationExceptionInterface
{
    /**
     * @return \Inferno\HttpFoundation\Exception\HeaderAlreadySentException
     */
    public static function forHeader(): HeaderAlreadySentException
    {
        return new static('Header already sent.');
    }
}
