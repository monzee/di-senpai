<?php

namespace Codeia\Di;

use Interop\Container\ContainerInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Composes containers.
 *
 * The sub-container is checked first before the super-container.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class ContainerGraph implements ContainerInterface {

    private $super;
    private $sub;

    /**
     * @param ContainerInterface $super The fallback container
     * @param ContainerInterface $sub   The priority container
     */
    function __construct(ContainerInterface $super, ContainerInterface $sub) {
        $this->super = $super;
        $this->sub = $sub;
        if ($sub instanceof AttachableContainer) {
            $sub->attach($this);
        }
    }

    function get($id) {
        if ($this->sub->has($id)) {
            return $this->sub->get($id);
        }
        return $this->super->get($id);
    }

    function has($id) {
        return $this->sub->has($id) || $this->super->has($id);
    }

}
