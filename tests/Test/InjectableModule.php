<?php

namespace Codeia\Test;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Description of InjectableModule
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class InjectableModule {

    public $mapping = [
        'root' => Root::class,
        'dep1' => Dep1::class,
        'dep2' => Dep2::class,
        'dep3' => Dep3::class,
        'subdep1' => SubDep1::class,
        'subdep2' => SubDep2::class,
    ];

    function root(Dep1 $a, Dep2 $b) {
        return new Root($a, $b);
    }

    function dep1(SubDep1 $dep) {
        return new Dep1($dep);
    }

    function dep2(SubDep2 $dep) {
        return new Dep2($dep);
    }

    function dep3(SubDep1 $dep) {
        return new Dep3($dep);
    }

    function subdep1(SubDep2 $dep) {
        return new SubDep1($dep);
    }

    function subdep2() {
        return new SubDep2;
    }

}
