<?php

namespace Codeia\Test;

/**
 * Description of Root
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class Root {
    public $a;
    public $b;
    public $c;

    function __construct(Dep1 $a, Dep2 $b, Dep3 $c = null) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }
}
