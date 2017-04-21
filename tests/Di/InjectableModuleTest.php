<?php

namespace Codeia\Di;

use PHPUnit\Framework\TestCase;
use Codeia\Test;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * Unit and functional tests for ObjectGraphBuilder.
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class InjectableModuleTest extends TestCase {

    const SCOPED = ObjectGraphBuilder::SCOPED;

    private $sut;

    /*
     * Root -----> Dep1 -----> SubDep1 <-,
     *    |`-----> Dep2 --------. |      |
     *    `------> Dep3 -----.  v v      |
     *                       | SubDep2   |
     *                       `-----------'
     */

    function setup() {
        $this->mod = new Test\InjectableModule;
        $this->sut = new ObjectGraphBuilder($this->mod);
    }

    function test_inflate_root_object() {
        $c = $this->sut->withServices($this->mod->mapping)->build();
        $root = $c->get(Test\Root::class);
        self::assertNotNull($root);
        self::assertInstanceOf(Test\Root::class, $root);
    }

    function test_unscoped_objects_are_unique() {
        $c = $this->sut->withServices($this->mod->mapping)->build();
        $a = $c->get(Test\Root::class);
        $b = $c->get(Test\Root::class);
        self::assertNotSame($a, $b);
    }

    function test_scoped_objects_are_identical() {
        $c = $this->sut->withScoped($this->mod->mapping)->build();
        $a = $c->get(Test\Root::class);
        $b = $c->get(Test\Root::class);
        self::assertSame($a, $b);
    }

    function test_scoped_dependencies_are_identical() {
        $c = $this->sut->withScoped($this->mod->mapping)->build();
        $a = $c->get(Test\Dep1::class);
        $b = $c->get(Test\Dep3::class);
        self::assertInstanceOf(Test\SubDep1::class, $a->a);
        self::assertInstanceof(Test\subdep1::class, $b->a);
        self::assertSame($a->a, $b->a);
    }

}
