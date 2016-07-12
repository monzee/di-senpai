<?php

namespace Codeia\Di;

use Interop\Container\ContainerInterface;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * A container that could defer to another container during resolution.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
interface AttachableContainer extends ContainerInterface {

    /**
     * Attaches a container to this container.
     *
     * It is up to the implementation how the attached container is utilized.
     * E.g. in {@see ObjectGraph} the ContainerInterface being passed to the
     * {@see Component} can be changed by attaching a different container. This
     * is automatically done by {@see ContainerGraph} when the subcontainer
     * is attachable.
     *
     * @param ContainerInterface $c The container to attach
     */
    function attach(ContainerInterface $c);

}

