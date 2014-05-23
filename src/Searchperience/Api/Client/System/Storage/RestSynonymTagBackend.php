<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\SynonymTag;
use Searchperience\Api\Client\Domain\Synonym\SynonymTagCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

class RestSynonymTagBackend extends AbstractRestBackend implements SynonymTagBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'synonymstag';

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymCollection
	 */
	public function getAll() {
		try {
			$response   = $this->getGetResponseFromEndpoint();
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new SynonymTagCollection();
		}

		return $this->buildSynonymTagsFromXml($xmlElement);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymTagCollection
	 */
	protected function buildSynonymTagsFromXml(\SimpleXMLElement $xml) {
		$synonymTagCollection = new SynonymTagCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$synonymTagCollection->setTotalCount((integer)$xml->totalCount->__toString());
		}

		$synonymTags = $xml->xpath('tag');
		foreach ($synonymTags as $synonymTag) {
			$synonymObject = new SynonymTag();
			$synonymObject->__setProperty('tagName',(string) $synonymTag);
			$synonymTagCollection->append($synonymObject);
		}

		return $synonymTagCollection;
	}
}
