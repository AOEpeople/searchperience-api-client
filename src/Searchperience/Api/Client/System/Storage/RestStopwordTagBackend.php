<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Stopword\StopwordTag;
use Searchperience\Api\Client\Domain\Stopword\StopwordTagCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestStopwordTagBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestStopwordTagBackend extends AbstractRestBackend implements StopwordTagBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'stopwordstag';

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordCollection
	 */
	public function getAll() {
		try {
			$response   = $this->getGetResponseFromEndpoint();
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new StopwordTagCollection();
		}

		return $this->buildStopwordTagsFromXml($xmlElement);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordTagCollection
	 */
	protected function buildStopwordTagsFromXml(\SimpleXMLElement $xml) {
		$stopwordTagCollection = new StopwordTagCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$stopwordTagCollection->setTotalCount((integer)$xml->totalCount->__toString());
		}

		$stopwordTags = $xml->xpath('tag');
		foreach ($stopwordTags as $stopwordTag) {
			$stopwordObject = new StopwordTag();
			$stopwordObject->__setProperty('tagName',(string) $stopwordTag);
			$stopwordTagCollection->append($stopwordObject);
		}

		return $stopwordTagCollection;
	}
}
