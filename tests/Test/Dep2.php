<?php

namespace Codeia\Test;

/**
 * Description of Dep2
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class Dep2 {
    public $a;

    function __construct(SubDep2 $a) {
        $this->a = $a;
    }
}
