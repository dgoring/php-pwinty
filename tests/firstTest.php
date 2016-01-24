<?php

use pwinty\PhpPwinty;

class PwintyTest extends PHPUnit_Framework_TestCase
{
	public function testSomething() {
		$this->assertTrue(true);
	}

    public function testCanBeNegated() {
        /*
        $a = new Money(1);

        $b = $a->negate();

        $this->assertEquals(-1, $b->getAmount());
        */

        $config = array(
            'api'        => 'sandbox',
            'merchantId' => 'xxxxxxxxxxxxxxxxx',
            'apiKey'     => 'xxxxxxxxxxxxxxxxx'
        );
        $pwinty = new PhpPwinty($config);

    }

}
