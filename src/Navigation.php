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
	 * Base path of our MD files.
	 * @var string
	 */
	protected $path;

	/**
	 * The current URI that is being accessed
	 * @var string
	 */
	protected $uri;

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
			$lang = $this->getTranslation('_breadcrumbs', $crumb, $langChain);

			$crumbs[$crumb] = $lang;
			$langChain .= '.';
		}

		return $crumbs;
	}

	/**
	 * @param string $slug
	 * @param string $slugPrefix
	 *
	 * @return string
	 */
	public function getTranslation($type, $slug, $slugPrefix)
	{
		// do an initial lookup of the slug prefix
		$lang = Arr::get($this->translations, $type . '.'  . $slugPrefix);

		// did we find an array structure?
		if (is_array($lang))
		{
			// fetch the slug from it
			$lang = Arr::get($lang, $slug);

			// if we hit an array again, we're on the wrong track
			if (is_array($lang))
			{
				$lang = null;
			}
		}

		// no hit on prefix? try the slug itself
		if ($lang === null)
		{
			$lang = Arr::get($this->translations, $slug);
		}

		// still no hit?
		if ($lang === null)
		{
			// check if we have a section title, and use the slug if there isn't one
			$lang = Arr::get(
				$this->translations,
				$type . '.' . $slugPrefix . '._title',
				$slug
			);
		}

		return $lang;
	}

}
