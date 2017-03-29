<?php

namespace Codeia\Di;

use RuntimeException;
use Psr\Container\ContainerExceptionInterface;

/*
 * This file is a part of the Di_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Thrown when a service's dependency also transitively depends on the service.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class CyclicDependencyError extends RuntimeException
    implements ContainerExceptionInterface {

    /**
     * @param array $path String names of the services being resolved when the
     *                    cycle was seen.
     */
    function __construct(array $path) {
        $root = $path[0];
        $p = implode(' -> ', $path);
        parent::__construct(
            "Cyclic dependency while resolving {$root}: [{$p}]", 500
        );
    }

}
