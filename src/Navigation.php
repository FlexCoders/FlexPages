<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages;

use Fuel\Common\Arr;

/**
 * Responsible for building navigation interfaces such as a content menu and
 * breadcrumb trails.
 */
class Navigation
{

	/**
	 * Contains translations for file names to be able to display nicer titles.
	 * @var array
	 */
	protected $translations = [];

	/**
	 * @param string $uri  Current request URI
	 * @param string $path Base path for MD files
	 */
	public function __construct($uri, $path)
	{
		$this->uri = $uri;
		$this->path = $path;
	}

	/**
	 * Sets "pretty" names for the folder structure, this means you can add extra
	 * formatting without having to put it in the file name.
	 *
	 * [
	 * 		// Top level keys will be used if a key cannot be found in the right place
	 * 		// In _breadcrumbs or _navigation
	 * 		'foo' => 'bar',
	 *
	 * 		'_breadcrumbs' => [
	 * 			'folder_name' => [
	 * 				// This is the name for 'folder_name'
	 * 				'_name' => 'Folder in the top level',
	 * 				'child1' => 'First Child',
	 * 				'child2' => 'Second Child',
	 * 			]
	 * 		],
	 *
	 * 		// Same format as _breadcrumbs
	 * 		'_navigation' => []
	 * ]
	 *
	 * @param array $translations
	 */
	public function setTranslations($translations)
	{
		$this->translations = $translations;
	}

	/**
	 * @return array
	 */
	public function getBreadcrumbList()
	{
		$crumbs = [];

		$langChain = '';

		// Work through our parts and see if there is a translation for them
		foreach (explode('/', $this->uri) as $crumb)
		{
			$langChain .= $crumb;

			// Check if we have a specific crumb translation
			$lang = Arr::get($this->translations, '_breadcrumbs.'.$langChain.'._name');

			// TODO: Clean this mess up.
			if ($lang === null)
			{
				$lang = Arr::get($this->translations, '_breadcrumbs.'.$langChain);

				if ($lang === null || is_array($lang))
				{
					$lang = Arr::get($this->translations, $crumb, $crumb);
				}
			}

			$crumbs[$crumb] = $lang;
			$langChain .= '.';
		}

		return $crumbs;
	}



}
