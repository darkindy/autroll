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

    public function testIsThereAnySyntaxError()
    {
        $var = new AutrollRunner;
        $this->assertTrue(is_object($var));
        unset($var);
    }

    public function testTroll()
    {
        $var = new AutrollRunner;
        $var->troll();
//        $this->assertTrue();
        unset($var);
    }

}
