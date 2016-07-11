<?php

namespace Codeia\Test;

/**
 * Description of Sakura
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class Sakura {
    /** @var \Codeia\Test\Emiya */
    private $sempai;
    private $dinner;

    function target() {
        return $this->sempai;
    }

    function other() {
        return $this->dinner;
    }
}
