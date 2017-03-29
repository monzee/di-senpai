<?php

namespace Codeia\Di;

use Psr\Container\ContainerInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

class Invoker {

    private $source;

    function __construct(ContainerInterface $source) {
        $this->source = $source;
    }

    /**
     * @param callable $fn         Function to inject and call
     * @return mixed               The result of the function call.
     * @throws UnknownServiceError When a parameter cannot be resolved.
     */
    function __invoke(callable $fn) {
        if (is_array($fn)) {
            $r = new \ReflectionMethod($fn[0], $fn[1]);
        } else {
            $r = new \ReflectionFunction($fn);
        }
        $args = [];
        foreach ($r->getParameters() as $p) {
            $type = $p->getClass();
            if ($type === null) {
                if ($p->isDefaultValueAvailable()) {
                    $args[] = $p->getDefaultValue();
                    continue;
                }
                $type = $p->getName();
            } else {
                $type = $type->getName();
            }
            if (!$this->source->has($type)) {
                throw new UnknownServiceError($type);
            }
            $args[] = $this->source->get($type);
        }
        return call_user_func_array($fn, $args);
    }

}
