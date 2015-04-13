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

	public function testBreadcrumbs()
	{
		$nav = new Navigation('foo', '');

		$this->assertEquals(
			['foo' => 'foo'],
			$nav->getBreadcrumbList()
		);

		$nav->setTranslations(['foo' => 'Bar']);

		$this->assertEquals(
			['foo' => 'Bar'],
			$nav->getBreadcrumbList()
		);
	}

	public function testChildBreadcrumbs()
	{
		$nav = new Navigation('foo/bar/baz/bat', '');

		$nav->setTranslations([
			'foo' => 'Bar',
			'_breadcrumbs' => [
				'foo' => [
					'bar' => [
						'_name' => 'ABAR',
						'baz' => [
							'_name' => '123',
						]
					]
				]
			]
		]);

		$this->assertEquals(
			['foo' => 'Bar', 'bar' => 'ABAR', 'baz' => '123', 'bat' => 'bat'],
			$nav->getBreadcrumbList()
		);
	}

}
