<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages;

/**
 * Common interface for markdown processors
 */
interface ParserInterface
{

	/**
	 * Takes a markdown document as a string and returns the built html
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function render($content);

}
