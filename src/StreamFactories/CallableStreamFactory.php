<?php

namespace danharper\Psr7JSONx\StreamFactories;

use danharper\Psr7JSONx\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class CallableStreamFactory implements StreamFactoryInterface {

	/**
	 * @var \Closure
	 */
	private $factory;

	public function __construct(\Closure $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @param string $content
	 * @return StreamInterface
	 */
	public function make($content)
	{
		return call_user_func($this->factory, $content);
	}

}