<?php

namespace Darkindy\Autroll;

use PHPUnit\Framework\TestCase;

/**
 * Tests for AutrollRunner
 *
 * @author Andrei Pietrușel
 */
class AutrollTest extends TestCase
{

    public function testTroll()
    {
        $var = new AutrollRunner;
        $var->troll();
//        $this->assertTrue();
        unset($var);
    }

}
