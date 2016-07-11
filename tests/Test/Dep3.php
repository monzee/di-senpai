<?php

namespace Codeia\Test;

/**
 * Description of Dep3
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class Dep3 {
    public $a;

    function __construct(SubDep1 $a) {
        $this->a = $a;
    }
}
