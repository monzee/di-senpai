<?php

namespace Codeia\Di;

use PHPUnit\Framework\TestCase;
use Codeia\Test;

/*
 * This file is a part of the DI_Senpai project.
 * See the LICENSE file at the project root for the terms of use.
 */

/**
 * sempai tests
 *
 * @author Mon Zafra &lt;mz@codeia.ph&gt;
 */
class SenpaiTest extends TestCase {

    function setup() {
        $this->con = new AutoResolve(new EmptyContainer);
        $this->shirou = new Senpai($this->con);
    }

    function test_returns_the_same_instance() {
        $aRock = new \stdclass;
        self::assertSame($aRock, $this->shirou->inject($aRock));
    }

    function test_injects_public_props() {
        $rin = new Test\Tohsaka;
        $this->shirou->inject($rin);
        self::assertNotNull($rin->tsun);
        self::assertInstanceOf(Test\Emiya::class, $rin->tsun);
    }

    function test_does_not_inject_private_props() {
        $sakura = new Test\Sakura;
        $this->shirou->inject($sakura);
        self::assertNull($sakura->target());
    }

    function test_does_not_inject_protected_props() {
        $ilya = new Test\Ilya;
        $this->shirou->inject($ilya);
        self::assertNull($ilya->target());
    }

    function test_does_not_inject_undocumented_props() {
        $rin = new Test\Tohsaka;
        $sakura = new Test\Sakura;
        $ilya = new Test\Ilya;
        $this->shirou->inject($rin);
        $this->shirou->inject($sakura);
        $this->shirou->inject($ilya);
        self::assertNull($rin->dere);
        self::assertNull($sakura->other());
        self::assertNull($ilya->other());
    }

    function test_does_not_inject_documented_but_unannotated_props() {
        $saber = new Test\Saber;
        $this->shirou->inject($saber);
        self::assertNull($saber->master);
    }

    function test_injects_private_when_forced() {
        $sakura = new Test\Sakura;
        $this->shirou->inject($sakura, Senpai::NO);
        self::assertNotNull($sakura->target());
        self::assertInstanceOf(Test\Emiya::class, $sakura->target());
    }

    function test_injects_protected_when_forced() {
        $ilya = new Test\Ilya;
        $this->shirou->inject($ilya, Senpai::NO);
        self::assertNotNull($ilya->target());
        self::assertInstanceOf(Test\Emiya::class, $ilya->target());
    }

    function test_properly_detects_var_annotations() {
        $o = new Test\VarAnnotations;
        $this->shirou->inject($o);
        self::assertNull($o->insideSingleQuotes);
        self::assertNull($o->insideDoubleQuotes);
        self::assertNull($o->insideBraces);
        self::assertNull($o->atSignWasEscaped);
        self::assertNotNull($o->leftSingleQuoteWasEscaped);
        self::assertNotNull($o->leftDoubleQuoteWasEscaped);
        self::assertNotNull($o->leftBraceWasEscaped);
        self::assertNotNull($o->afterSqString);
        self::assertNotNull($o->afterDqString);
        self::assertNotNull($o->afterBlock);
        self::assertNotNull($o->afterNestedBlocks);
        self::assertNull($o->afterImproperlyClosedBlocks);
    }

    /** @expectedException PHPUnit_Framework_Error_Notice */
    function test_sends_notice_when_class_is_not_resolvable() {
        $o = new Test\VarAnnotations;
        $this->shirou->inject($o, Senpai::NO);
    }

    function test_recursive_injection() {
        self::markTestSkipped("TODO: do i want this?");
    }

}
