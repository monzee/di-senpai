<?php

namespace Codeia\Di;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Thrown when AutoResolve was asked to resolve an interface or abstract class.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class CannotAutoResolveError extends UnknownServiceError {

    function __construct($name, $code = 500) {
        \InvalidArgumentException::__construct(
            "{$name} service does not exist or is not instantiable. " .
            "Interfaces and abstract classes need to be explicitly provided.",
            $code
        );
    }

}
