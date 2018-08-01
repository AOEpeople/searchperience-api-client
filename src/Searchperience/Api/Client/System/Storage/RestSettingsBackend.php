<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Settings\Language;
use Searchperience\Api\Client\Domain\Settings\LanguageCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;
use SimpleXMLElement;

/**
 * Class RestSettingsBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestSettingsBackend extends AbstractRestBackend implements SettingsBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'settings';

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Settings\LanguageCollection
	 */
	public function getLanguages() {
        try {
            $response   = $this->getGetResponseFromEndpoint();
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return new LanguageCollection();
        }

        return $this->buildLanguagesFromXml($xmlElement);
    }

	/**
	 * @param SimpleXMLElement $xml
	 *
	 * @return LanguageCollection
	 */
	protected function buildLanguagesFromXml(SimpleXMLElement $xml) {
		$languageCollection = new LanguageCollection();
		$languages = $xml->xpath('/settings/languages/language');

		foreach ($languages as $language) {
            $languageAttributeArray = (array)$language->attributes();
			$languageObject = new Language();
            $languageObject->__setProperty('name',(string) $languageAttributeArray['@attributes']['name']);
			$languageCollection->append($languageObject);
		}

		return $languageCollection;
	}
}
