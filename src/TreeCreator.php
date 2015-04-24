<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages;

use InvalidArgumentException;

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
	 * Returns an array representing the folder structure
	 *
	 * @param string $dir
	 * @param int $order
	 *
	 * @return array
	 *
	 * @throws InvalidArgumentException if the given directory is not readable.
	 */
	public function build($dir, $order = 33)
	{
		if ( ! is_readable($dir))
		{
			throw new InvalidArgumentException('Cannot read '.$dir);
		}

		return $this->readDir($dir, $order);
	}

	/**
	 * @param string $dir
	 *
	 * @return array
	 */
	protected function readDir($dir, $order)
	{
		$splitEntries = ($order & static::SORT_FILES_FIRST) || ($order & static::SORT_FOLDERS_FIRST);

		$files = [];
		$folders = [];

		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle)))
		{
			if ($entry == '..' || $entry == '.')
			{
				continue;
			}

			$filename = $dir . DIRECTORY_SEPARATOR . $entry;
			if (is_file($filename))
			{
				$files[$entry] = $entry;
			}
			else if (is_dir($filename))
			{
				if ($splitEntries)
				{
					$folders[$entry] = $this->readDir($filename, $order);
				}
				else
				{
					$files[$entry] = $this->readDir($filename, $order);
				}
			}
		}

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

		if ($order & static::SORT_FOLDERS_FIRST)
		{
			return $folders + $files;
		}

		return $files + $folders;
	}

}
