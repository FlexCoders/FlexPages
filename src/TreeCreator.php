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
 * 	'files' => ['file1.md', 'file2.md'],
 * 	'folders' => ['folder1' => ['files' => [], 'folders' => []] ],
 * ]
 */
class TreeCreator
{

	/**
	 * Returns an array representing the folder structure
	 *
	 * @param string $dir
	 *
	 * @return array
	 *
	 * @throws InvalidArgumentException if the given directory is not readable.
	 */
	public function build($dir)
	{
		if ( ! is_readable($dir))
		{
			throw new InvalidArgumentException('Cannot read '.$dir);
		}

		return $this->readDir($dir);
	}

	/**
	 * @param string $dir
	 *
	 * @return array
	 */
	protected function readDir($dir)
	{
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
				$files[] = $entry;
			}
			else if (is_dir($filename))
			{
				$folders[$entry] = $this->readDir($filename);
			}
		}

		sort($files);
		ksort($folders);

		return [
			'files' => $files,
			'folders' => $folders,
		];
	}

}
