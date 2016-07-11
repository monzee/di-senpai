<?php

namespace Codeia\Di;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Builds {@see Component}s and {@see ObjectGraph}s that use them.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class ObjectGraphBuilder {

    const SCOPED = true;

    private $services = [];
    private $scoped = [];
    private $module;

    /**
     * @param mixed $provider The provider/module/factory instance to pass
     *                        to the created component
     */
    function __construct($provider) {
        $this->module = $provider;
    }

    /**
     * @param string $method      Name of a provider method
     * @param string|array $types The type or types that the method provides
     * @param bool $scoped        Whether or not the instance is saved by the
     *                            component
     * @return $this
     */
    function bind($method, $types, $scoped = false) {
        list($which, $other) = $scoped ?
            ['scoped', 'services'] :
            ['services', 'scoped'];
        if (is_int($method)) {
            if (is_array($types)) {
                foreach ($types as $k => $v) {
                    $this->bind($k, $v, $scoped);
                }
                return $this;
            }
            $method = $types;
        }
        if (!is_array($types)) {
            $this->{$which}[$types] = $method;
            unset($this->{$other}[$types]);
            return $this;
        }
        foreach ($types as $type) {
            $this->{$which}[$type] = $method;
            unset($this->{$other}[$type]);
        }
        return $this;
    }

    /**
     * Bind many methods at once.
     *
     * @param array $services Map of method names to type or list of types.
     * @return $this
     */
    function withServices(array $services) {
        foreach ($services as $method => $type) {
            $this->bind($method, $type);
        }
        return $this;
    }

    /**
     * Bind many scoped methods at once.
     *
     * @param array $services Map of method names to type or list of types.
     * @return $this
     */
    function withScoped(array $services) {
        foreach ($services as $method => $type) {
            $this->bind($method, $type, self::SCOPED);
        }
        return $this;
    }

    /**
     * @return ObjectGraph
     */
    function build() {
        $c = new Component($this->module, $this->services, $this->scoped);
        return new ObjectGraph($c);
    }

}
