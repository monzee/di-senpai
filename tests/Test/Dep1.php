<?php

namespace Codeia\Test;

/**
 * Description of Dep1
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class Dep1 {
    public $a;

    function __construct(SubDep1 $a) {
        $this->a = $a;
    }
}
