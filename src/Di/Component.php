<?php

namespace Codeia\Di;

use Interop\Container\ContainerInterface;

/*
 * This file is a part of the Bloom project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Pseudo-container that calls module methods to produce service instances.
 *
 * A module can be any object, it doesn't have to implement any interface.
 * Provision methods are called with an instance of ContainerInterface.
 * This class is typically not directly instantiated. Use an
 * {@see ObjectGraphBuilder} to create {@see ObjectGraph}s that use components.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 * @todo provide values
 */
class Component {

    private $services = [];
    private $scopedServices = [];
    private $scope = [];
    private $make;

    /**
     * @param mixed $module   Object to call the provision methods on.
     * @param array $services Map from service names to module method names.
     * @param array $scoped   Same. Scoped services are shared between all
     *                        services that depend on them.
     */
    function __construct($module, array $services, array $scoped) {
        $this->make = $module;
        $this->services = $services;
        $this->scopedServices = $scoped;
    }

    /**
     * @param string $type
     * @param ContainerInterface $context
     * @return mixed
     * @throws UnknownServiceError
     */
    function make($type, ContainerInterface $context) {
        if (array_key_exists($type, $this->services)) {
            $method = $this->services[$type];
            return $this->make->$method($context);
        } else if (array_key_exists($type, $this->scopedServices)) {
            $method = $this->scopedServices[$type];
            if (!array_key_exists($method, $this->scope)) {
                $this->scope[$method] = $this->make->$method($context);
            }
            return $this->scope[$method];
        } else if ($type === ContainerInterface::class) {
            return $context;
        }
        throw new UnknownServiceError($type);
    }

    /**
     * @param string $key
     * @return bool
     */
    function provides($key) {
        return array_key_exists($key, $this->services)
            || array_key_exists($key, $this->scopedServices);
    }

}
