<?php

namespace Codeia\Di;

use PHPUnit\Framework\TestCase;
use Codeia\Test;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * invoker tests
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class InvokerTest extends TestCase {

    function setup() {
        $this->con = new AutoResolve(new EmptyContainer);
        $this->carl = new Invoker($this->con);
    }

    function test_can_call_a_lambda_and_returns_its_result() {
        self::assertEquals(1024, $this->carl->__invoke(function () {
            return 1024;
        }));
    }

    function test_can_call_a_method_and_returns_its_result() {
        self::assertSame($this->carl, $this->carl->__invoke([$this, 'foo']));
        self::assertEquals('BAR', $this->carl->__invoke([self::class, 'bar']));
    }

    function foo() {
        return $this->carl;
    }

    static function bar() {
        return 'BAR';
    }

    function test_injects_lambda_parameters() {
        $e = $this->carl->__invoke(function (Test\Tohsaka $t) {
            return $t;
        });
        self::assertNotNull($e);
        // TODO: do i want to inject the arguments' fields? separate method?
        self::assertNull($e->tsun);
    }

    function test_injects_method_parameters() {
        $e = $this->carl->__invoke([$this, 'e']);
        self::assertNotNull($e);
        self::assertSame($e, $this->carl->__invoke([self::class, 's']));
    }

    function e(Test\Emiya $e) {
        return $e;
    }

    static function s(Test\Emiya $e) {
        return $e;
    }

}
