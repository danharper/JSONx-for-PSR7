<?php

namespace danharper\Psr7JSONx;

use danharper\JSONx\JSONx;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class JSONxResponseAdaptor {

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

	public function handle(RequestInterface $request, ResponseInterface $response)
	{
		if ($this->shouldConvertJsonToXml($request, $response))
		{
			return $response
				->withHeader('Content-Type', 'application/xml')
				->withBody($this->toJSONx($response->getBody()));
		}
		else
		{
			return $response;
		}
	}

	public function toJSONx(StreamInterface $stream)
	{
		return $this->streamFactory->make($this->converter->toJSONx(json_decode($stream)));
	}

	/**
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @return bool
	 */
	private function shouldConvertJsonToXml(RequestInterface $request, ResponseInterface $response)
	{
		$requestWantsXml = in_array('application/xml', $request->getHeader('Accept'));

		$responseIsJson = in_array('application/json', $response->getHeader('Content-Type'));

		return $requestWantsXml && $responseIsJson;
	}

}