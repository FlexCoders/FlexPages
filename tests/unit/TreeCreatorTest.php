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
		// fetch the default tree
		$result = $this->creator->build(__DIR__.'/../_data/simple_tree');

		// verify all results are there
		$this->assertArrayHasKey('test.md', $result);
		$this->assertArrayHasKey('something-else.md', $result);
		$this->assertArrayHasKey('folder', $result);
		$this->assertArrayHasKey('child.md', $result['folder']);

		$expected = [
			'something-else.md' => 'something-else.md',
			'test.md' => 'test.md',
			'folder' => [
				'child.md' => 'child.md'
			],
		];

		$this->assertSame(
			$expected, $result
		);
	}

	public function testSimpleTreeDescendingOrder()
	{
		// fetch the default tree
		$result = $this->creator->build(__DIR__.'/../_data/simple_tree', TreeCreator::SORT_DESCENDING);

		// verify all results are there
		$this->assertArrayHasKey('test.md', $result);
		$this->assertArrayHasKey('something-else.md', $result);
		$this->assertArrayHasKey('folder', $result);
		$this->assertArrayHasKey('child.md', $result['folder']);

		$expected = [
			'test.md' => 'test.md',
			'something-else.md' => 'something-else.md',
			'folder' => [
				'child.md' => 'child.md'
			],
		];

		$this->assertSame(
			$expected, $result
		);
	}

	public function testSimpleTreeFoldersFirst()
	{
		// fetch the default tree
		$result = $this->creator->build(__DIR__.'/../_data/simple_tree', TreeCreator::SORT_FOLDERS_FIRST);

		// verify all results are there
		$this->assertArrayHasKey('test.md', $result);
		$this->assertArrayHasKey('something-else.md', $result);
		$this->assertArrayHasKey('folder', $result);
		$this->assertArrayHasKey('child.md', $result['folder']);

		$expected = [
			'folder' => [
				'child.md' => 'child.md'
			],
			'something-else.md' => 'something-else.md',
			'test.md' => 'test.md',
		];

		$this->assertSame(
			$expected, $result
		);
	}

	public function testSimpleTreeFilesDescendingFirst()
	{
		// fetch the default tree
		$result = $this->creator->build(__DIR__.'/../_data/simple_tree', TreeCreator::SORT_FOLDERS_FIRST|TreeCreator::SORT_DESCENDING);

		// verify all results are there
		$this->assertArrayHasKey('test.md', $result);
		$this->assertArrayHasKey('something-else.md', $result);
		$this->assertArrayHasKey('folder', $result);
		$this->assertArrayHasKey('child.md', $result['folder']);

		$expected = [
			'folder' => [
				'child.md' => 'child.md'
			],
			'test.md' => 'test.md',
			'something-else.md' => 'something-else.md',
		];

		$this->assertSame(
			$expected, $result
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
