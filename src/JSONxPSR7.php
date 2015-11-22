<?php namespace danharper\Psr7JSONx;

use danharper\JSONx\JSONx;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class JSONxPSR7 {

	/**
	 * @var JSONxRequestAdaptor
	 */
	private $requestAdaptor;

	/**
	 * @var JSONxResponseAdaptor
	 */
	private $responseAdaptor;

	public function __construct(StreamFactoryInterface $streamFactory)
	{
		$converter = new JSONx;
		$this->requestAdaptor = new JSONxRequestAdaptor($converter, $streamFactory);
		$this->responseAdaptor = new JSONxResponseAdaptor($converter, $streamFactory);
	}

	public function request(RequestInterface $request)
	{
		return $this->requestAdaptor->handle($request);
	}

	public function response(RequestInterface $request, ResponseInterface $response)
	{
		return $this->responseAdaptor->handle($request, $response);
	}

}