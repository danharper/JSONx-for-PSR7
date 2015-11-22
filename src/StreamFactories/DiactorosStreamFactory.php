<?php

namespace danharper\Psr7JSONx\StreamFactories;

use danharper\Psr7JSONx\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\Stream;

class DiactorosStreamFactory implements StreamFactoryInterface {

	/**
	 * @param string $content
	 * @return StreamInterface
	 */
	public function make($content)
	{
		$stream = new Stream('php://temp', 'wb+');

		$stream->write($content);

		return $stream;
	}

}