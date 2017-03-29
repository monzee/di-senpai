<?php

namespace Codeia\Di;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Thrown when a service is not registered in the container but still called
 * get() on.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class UnknownServiceError extends InvalidArgumentException
    implements NotFoundExceptionInterface {

    /**
     * @param string $name The name of the service
     * @param int $code
     */
    function __construct($name, $code = 500) {
        parent::__construct("Unknown service: {$name}", $code);
    }

}
