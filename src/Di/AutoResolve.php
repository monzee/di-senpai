<?php

namespace Codeia\Di;

use Psr\Container\ContainerInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Tries to resolve services by treating them as names of concrete classes.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class AutoResolve implements ContainerInterface {

    private $source;
    private $resolving = [];
    private $scope = [];
    private $resolvable = [];

    /**
     * @param ContainerInterface $container Subcontainer to check before
     *                                      trying to resolve. Use
     *                                      {@see EmptyContainer} if you have
     *                                      nothing.
     */
    function __construct(ContainerInterface $container) {
        $this->source = $container;
    }

    function get($id) {
        if ($this->source->has($id)) {
            return $this->source->get($id);
        } else if ($this->maybeResolvable($id)) {
            if (!array_key_exists($id, $this->scope)) {
                $this->scope[$id] = $this->resolve($id);
            }
            return $this->scope[$id];
        } else if ($id === ContainerInterface::class) {
            return $this;
        }
        throw new CannotAutoResolveError($id);
    }

    function has($id) {
        return $this->source->has($id) || $this->maybeResolvable($id);
    }

    /**
     * Replaces the wrapped container with the return value of the callable.
     *
     * @param callable $fn Function of type
     *                     ContainerInterface -> ContainerInterface
     */
    function tap(callable $fn) {
        $this->source = $fn($this->source);
    }

    private function resolve($className) {
        $this->ensureNoCycles($className);
        $cls = new \ReflectionClass($className);
        $cons = $cls->getConstructor();
        if (empty($cons)) {
            $instance = $cls->newInstance();
        } else {
            $args = array_map(function (\ReflectionParameter $p) {
                $type = $p->getClass();
                if (!empty($type)) {
                    return $this->get($type->getName());
                }
                // TODO: try to resolve as uri
                // probably should be the first thing to try
                // also should probably be in a subclass/sibling
                // method:<fqcn>/<method>?<param>
                // e.g. method:Foo%5CBar%5CBaz/__construct?theParam
                $name = $p->getName();
                if ($this->source->has($name)) {
                    return $this->get($name);
                }
                if ($p->isDefaultValueAvailable()) {
                    return $p->getDefaultValue();
                }
                throw new CannotAutoResolveError($name);
            }, $cons->getParameters());
            $instance = $cls->newInstanceArgs($args);
        }
        array_pop($this->resolving);
        return $instance;
    }

    private function maybeResolvable($service) {
        if (!array_key_exists($service, $this->resolvable)) {
            $this->resolvable[$service] = class_exists($service) &&
                ((new \ReflectionClass($service))->isInstantiable());
        }
        return $this->resolvable[$service];
    }

    private function ensureNoCycles($name) {
        if (in_array($name, $this->resolving)) {
            throw new CyclicDependencyError($this->resolving);
        }
        $this->resolving[] = $name;
    }
}
