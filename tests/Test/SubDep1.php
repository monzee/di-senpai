<?php

namespace Codeia\Test;

/**
 * Description of SubDep1
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class SubDep1 {
    public $a;

    function __construct(SubDep2 $a) {
        $this->a = $a;
    }
}
