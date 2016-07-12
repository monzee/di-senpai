<?php

namespace Codeia\Di;

use Interop\Container\ContainerInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * You'd probably want to use a {@see ObjectGraphBuilder} to make one of these.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class ObjectGraph implements AttachableContainer {

    private $component;
    private $resolving = [];
    private $container;

    /**
     * @param Component $c Components are typically created through the
     *                     ObjectGraphBuilder.
     */
    function __construct(Component $c) {
        $this->component = $c;
    }

    function attach(ContainerInterface $c) {
        $this->container = $c;
    }

    function get($id) {
        if ($this->component->provides($id)) {
            $this->ensureNoCycles($id);
            $instance = $this->component->make($id, $this->container ?: $this);
            array_pop($this->resolving);
            return $instance;
        }
        throw new UnknownServiceError($id);
    }

    function has($id) {
        return $this->component->provides($id);
    }

    private function ensureNoCycles($name) {
        if (in_array($name, $this->resolving)) {
            throw new CyclicDependencyError($this->resolving);
        }
        $this->resolving[] = $name;
    }


}
