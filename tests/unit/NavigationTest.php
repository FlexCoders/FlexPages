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

class NavigationTest extends Test
{

	/**
	 * breadcrumbs without translation
	 */
	public function testBasicBreadcrumbs()
	{
		$tree = (new TreeCreator)
			->build(__DIR__.'/../_data/simple_tree');

		$nav = new Navigation($tree, 'test.md', '');

		$this->assertEquals(
			['test.md' => 'test.md'],
			$nav->getBreadcrumbList()
		);
	}

	/**
	 * breadcrumbs with translation
	 */
	public function testTranslatedBreadcrumbs()
	{
		$tree = (new TreeCreator)
			->setTranslations(['test.md' => 'FooBar'])
			->build(__DIR__.'/../_data/simple_tree');

		$nav = new Navigation($tree, 'test.md', '');

		$this->assertEquals(
			['test.md' => 'FooBar'],
			$nav->getBreadcrumbList()
		);
	}

	public function testChildBreadcrumbs()
	{
		$tree = (new TreeCreator)
			->setTranslations([
				'folder' => [
					'__title' => 'Bar',
					'child.md' => 'ABAR',
				]
			])
			->build(__DIR__.'/../_data/simple_tree');

		$nav = new Navigation($tree, 'folder/child.md', '');

		$this->assertEquals(
			['folder' => 'Bar', 'child.md' => 'ABAR'],
			$nav->getBreadcrumbList()
		);
	}

}
