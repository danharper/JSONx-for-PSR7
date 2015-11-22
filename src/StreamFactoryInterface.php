<?php

namespace danharper\Psr7JSONx;

use Psr\Http\Message\StreamInterface;

interface StreamFactoryInterface {

	/**
	 * @param string $content
	 * @return StreamInterface
	 */
	public function make($content);

}