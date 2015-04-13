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
use InvalidArgumentException;

class TreeCreatorTest extends Test
{

	/**
	 * @var TreeCreator
	 */
	protected $creator;

	protected function _before()
	{
		$this->creator = new TreeCreator;
	}

	public function testSimpleTree()
	{
		$result = $this->creator->build(__DIR__.'/../_data/simple_tree');

		$this->assertArrayHasKey('files', $result);
		$this->assertArrayHasKey('folders', $result);

		$this->assertEquals(
			['something-else.md', 'test.md',],
			$result['files']
		);

		$this->assertArrayHasKey(
			'folder',
			$result['folders']
		);

		$this->assertEquals(
			['child.md'],
			$result['folders']['folder']['files']
		);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidDir()
	{
		$this->creator->build('fakepath');
	}

}
