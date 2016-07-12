<?php

namespace Codeia\Di;

use PHPUnit\Framework\TestCase;
use Codeia\Test;

class ContainerGraphTest extends TestCase {

    function test_service_in_super_but_not_sub_should_be_found() {
        $service = new \stdclass;
        $super = new ValueContainer(['foo' => $service]);
        $sub = new EmptyContainer();
        $composed = new ContainerGraph($super, $sub);
        self::assertTrue($composed->has('foo'));
        self::assertSame($service, $composed->get('foo'));
    }

    function test_sub_can_pull_deps_from_super() {
        $sub = (new ObjectGraphBuilder(new Test\BaseModule))
            ->bind('subdep1', Test\SubDep1::class)
            ->build();
        $subdep2 = new Test\SubDep2;
        $super = new ValueContainer([Test\SubDep2::class => $subdep2]);
        $composed = new ContainerGraph($super, $sub);
        self::assertSame($subdep2, $composed->get(Test\SubDep1::class)->a);
    }

}

