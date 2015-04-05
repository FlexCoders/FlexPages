<?php

namespace FlexCoders\FlexPages;

use Codeception\TestCase\Test;

class TmpTest extends Test
{

	public function testReturn()
	{
		$t = new Tmp;
		$this->assertTrue($t->returnTrue());
	}

}
