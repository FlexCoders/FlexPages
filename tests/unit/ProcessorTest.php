<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages;

use Codeception\TestCase\Test;

class ProcessorTest extends Test
{

	/**
	 * @var Processor
	 */
	protected $processor;

	protected function _before()
	{
		$this->processor = new Processor(__DIR__.'/../_data/simple_tree');
	}

	public function testTopLevelPage()
	{
		$result = $this->processor->buildPageContent('test');
		$this->assertEquals(
			"<h1>Test page!</h1>\n",
			$result
		);
	}

	public function testSubPage()
	{
		$result = $this->processor->buildPageContent('folder/child');
		$this->assertEquals(
			"<h1>Child page!</h1>\n",
			$result
		);
	}

	/**
	 * @expectedException \FlexCoders\FlexPages\UnknownPageException
	 */
	public function testInvalidPage()
	{
		$this->processor->buildPageContent('notvalid');
	}

	/**
	 * @expectedException \FlexCoders\FlexPages\UnknownPageException
	 */
	public function testAccessOutsidePath()
	{
		$this->processor->buildPageContent('../outside');
	}

}
