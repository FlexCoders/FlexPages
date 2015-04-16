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
 * Responsible for building requested pages.
 */
class Processor
{

	/**
	 * Base path that contains our MD files
	 * @var string
	 */
	protected $path;

	/**
	 * @var ParserInterface
	 */
	protected $parser;

	/**
	 * Class name to use as the default parser if one is not supplied in the constructor.
	 * @var string
	 */
	protected $defaultParser = 'FlexCoders\FlexPages\Parser\CommonMark';

	/**
	 * @param string          $path   Base path where the MD files are stored.
	 * @param ParserInterface $parser
	 */
	public function __construct($path, ParserInterface $parser = null)
	{
		$this->path = $path;

		if ($parser == null)
		{
			$parser = new $this->defaultParser();
		}

		$this->parser = $parser;
	}

	/**
	 * Builds the requested page content and returns the html in a string.
	 *
	 * @param string $uri
	 *
	 * @return string
	 */
	public function buildPageContent($uri)
	{
		$content = $this->loadFileContent($uri);
		return $this->parser->render($content);
	}

	/**
	 * @param string $uri
	 *
	 * @return string
	 *
	 * @throws UnknownPageException
	 */
	protected function loadFileContent($uri)
	{
		$realPath = $this->getMarkdownPath($uri);

		if ( ! is_file($realPath) )
		{
			// Try for an index.md
			$realPath = $this->getMarkdownPath($uri.'/index');
			if ( ! is_file($realPath) )
			{
				throw new UnknownPageException(
					$uri . ' is not a known markdown file.'
				);
			}
		}

		return file_get_contents($realPath);
	}

	/**
	 * Returns the file path that the given uri represents
	 *
	 * @param $uri
	 *
	 * @return string
	 */
	protected function getMarkdownPath($uri)
	{
		$filePath = $this->path .
			// Remove any "level up" directory indicators.
			str_replace(['/..', '\..'], ['', ''], '/' . $uri . '.md');

		return realpath($filePath);
	}

}
