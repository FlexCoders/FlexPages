<?php
/**
 * @package    FlexPages
 * @copyright  2015 FlexCoders Ltd
 * @license    MIT
 * @link       https://github.com/FlexCoders/FlexPages
 * @author     FlexCoders Ltd
 */

namespace FlexCoders\FlexPages\Parser;

use FlexCoders\FlexPages\ParserInterface;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;

/**
 * Adaptor for league/commonmark package for markdown parsing.
 * @link http://commonmark.thephpleague.com/
 */
class CommonMark implements ParserInterface
{

	/**
	 * @var Environment
	 */
	protected $environment;

	public function __construct($environment = null)
	{
		if ($environment === null)
		{
			$environment = Environment::createCommonMarkEnvironment();
		}

		$this->environment = $environment;
	}

	/**
	 * @return Environment
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * @param Environment $environment
	 */
	public function setEnvironment($environment)
	{
		$this->environment = $environment;
	}

	/**
	 * Takes a markdown document as a string and returns the built html
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function render($content)
	{
		$parser = new DocParser($this->environment);
		$htmlRenderer = new HtmlRenderer($this->environment);
		$document = $parser->parse($content);
		return $htmlRenderer->renderBlock($document);
	}

}
