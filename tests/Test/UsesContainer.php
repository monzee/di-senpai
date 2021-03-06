<?php

namespace Codeia\Test;

use Psr\Container\ContainerInterface;

/**
 * Description of UsesContainer
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class UsesContainer {
    public $container;

    function __construct(ContainerInterface $c) {
        $this->container = $c;
    }
}
