<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages;

use Closure;
use InvalidArgumentException;
use Fuel\Common\Arr;

/**
 * Takes a folder and reads the content to create a tee structure of pages.
 *
 * Builds an array like:
 * [
 *     'file1.md' => 'file1.md',
 *     'folder1' => [
 *         'folder1-file1.md' => 'folder1-file1.md'
 *     ],
 *     'file2.md' => 'file2.md',
 * ]
 *
 * or in case a translation array is used, perhaps something like:
 * [
 *     'file1.md' => 'The first file',
 *     'folder1' => [
 *         '__title' => 'Folder number 1',
 *         'folder1-file1.md' => 'The first child'
 *     ],
 *     'file2.md' => 'The second file',
 * ]
 */
class TreeCreator
{
	/**
	 * Sort order constants
	 */
	const SORT_NONE = 0;
	const SORT_ASCENDING = 1;
	const SORT_DESCENDING = 2;

	/**
	 * Type order constants
	 */
	const SORT_FILES_FIRST = 32;
	const SORT_FOLDERS_FIRST = 64;

	/**
	 * Contains translations for file names to be able to display nicer titles.
	 * @var array
	 */
	protected $translations = [];

	/**
	 * Closure to pre-process the filename found before attempting to translate it
	 * @var Closure
	 */
	protected $preProcessor;

	/**
	 * Returns an array representing the folder structure
	 *
	 * @param string $dir
	 * @param int $order, default SORT_FOLDERS_FIRST|SORT_ASCENDING
	 *
	 * @return array
	 *
	 * @throws InvalidArgumentException if the given directory is not readable.
	 */
	public function build($dir, $order = 65)
	{
		if ( ! is_readable($dir))
		{
			throw new InvalidArgumentException('Cannot read '.$dir);
		}

		return $this->readDir($dir, $order, $this->translations);
	}

	/**
	 * Sets "pretty" names for the folder structure, this means you can add extra
	 * formatting without having to put it in the file name. The structure of the
	 * array should mimic the folder structure.
	 *
	 * [
	 *     'file1' => 'The first file',
	 *     'folder1' => [
	 *         'child1' => 'First Child',
	 *         'child2' => 'Second Child',
	 *     ],
	 *     'file3' => 'The third file',
	 * ]
	 *
	 * @param array $translations
	 *
	 * @return self
	 */
	public function setTranslations($translations)
	{
		$this->translations = $translations;

		return $this;
	}

	/**
	 * Optional closure to pre-process filenames before attempting to
	 * translate them. This could include things like stripping sequence
	 * numbers or file extensions.
	 *
	 * @param Closure $preProcessor
	 *
	 * @return self
	 */
	public function setPreProcessor(Closure $preProcessor)
	{
		$this->preProcessor = $preProcessor;

		return $this;
	}

	/**
	 * @param string $dir
	 *
	 * @return array
	 */
	protected function readDir($dir, $order, $translations)
	{
		// check if we need to split entries
		$splitEntries = ($order & static::SORT_FILES_FIRST) || ($order & static::SORT_FOLDERS_FIRST);

		// temporary storage for results
		$files = [];
		$folders = [];

		if ($title = Arr::get($translations, '__title'))
		{
			if ($splitEntries)
			{
				$folders['__title'] = $title;
			}
			else
			{
				$files['__title'] = $title;
			}
		}

		// loop over the given folder
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle)))
		{
			// skip directory entries
			if ($entry == '..' || $entry == '.')
			{
				continue;
			}

			// construct the FQFN
			$filename = $dir . DIRECTORY_SEPARATOR . $entry;

			// if it's a file...
			if (is_file($filename))
			{
				// do we need preprocessing of the filename found?
				if ($this->preProcessor instanceOf Closure)
				{
					$file = $this->preProcessor->__invoke($entry);
				}
				else
				{
					$file = $entry;
				}

				// translate the filename (with or without extension) if needed
				$files[$entry] = Arr::get($translations, $file, $entry);
			}

			// else it must be a directory...
			elseif (is_dir($filename))
			{
				// recurse to add the folder contents
				$result = $this->readDir($filename, $order, Arr::get($translations, $entry, []));

				// store the result in the correct result array
				if ($splitEntries)
				{
					$folders[$entry] = $result;
				}
				else
				{
					$files[$entry] = $result;
				}
			}
		}

		// sort the result if needed
		if ($order & static::SORT_ASCENDING)
		{
			ksort($files);
			ksort($folders);
		}
		elseif ($order & static::SORT_DESCENDING)
		{
			krsort($files);
			krsort($folders);
		}

		// and return in the order requested
		if ($order & static::SORT_FOLDERS_FIRST)
		{
			return $folders + $files;
		}

		return $files + $folders;
	}

}
