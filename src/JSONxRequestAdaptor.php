<?php

namespace danharper\Psr7JSONx;

use danharper\JSONx\JSONx;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

class JSONxRequestAdaptor {

	/**
	 * @var JSONx
	 */
	private $converter;

	/**
	 * @var StreamFactoryInterface
	 */
	private $streamFactory;

	public function __construct(JSONx $converter, StreamFactoryInterface $streamFactory)
	{
		$this->converter = $converter;
		$this->streamFactory = $streamFactory;
	}

	public function handle(RequestInterface $request)
	{
		if ($this->shouldConvertXmlToJson($request))
		{
			return $request
				->withHeader('Content-Type', 'application/json')
				->withBody($this->fromJSONx($request->getBody()));
		}
		else
		{
			return $request;
		}
	}

	private function fromJSONx(StreamInterface $body)
	{
		return $this->streamFactory->make(json_encode($this->converter->fromJSONx((string) $body)));
	}

	/**
	 * @param RequestInterface $request
	 * @return bool
	 */
	private function shouldConvertXmlToJson(RequestInterface $request)
	{
		return in_array('application/xml', $request->getHeader('Content-Type'));
	}

}