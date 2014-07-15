<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 10/07/14
 * Time: 16:22
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\AdminSearch\AdminSearch;
use Searchperience\Api\Client\Domain\AdminSearch\AdminSearchCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;
use SebastianBergmann\Exporter\Exception;


/**
 * Class RestAdminSearchBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestAdminSearchBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend {
	/**
	 * @var string
	 */
	protected $endpoint = 'adminsearches';

	/**
	 * @return array|AdminSearchCollection
	 */
	public function getAll() {
		try {
			$response = $this->getGetResponseFromEndpoint();
			return $this->buildFromXml($response->xml());
		} catch (EntityNotFoundException $e) {
			return new AdminSearchCollection();
		}
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return array|AdminSearchCollection
	 */
	protected function buildFromXml(\SimpleXMLElement $xml) {
		$adminSearchCollection = new AdminSearchCollection();

		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$adminSearchCollection->setTotalCount((integer) $xml->totalCount->__toString());
		}

		$adminsearches = $xml->xpath('adminsearch');
		foreach($adminsearches as $adminsearch) {
			$adminsearchAttributeArray = (array)$adminsearch->attributes();

			$adminSearchObject = new AdminSearch();
			$adminSearchObject->__setProperty('id',(string)$adminsearchAttributeArray['@attributes']['id']);
			$adminSearchObject->__setProperty('title',(string)$adminsearch->title);
			$adminSearchObject->__setProperty('description',(string)$adminsearch->description);
			$adminSearchObject->__setProperty('url',(string)$adminsearch->url);

			$adminSearchCollection[] = $adminSearchObject;

			$adminSearchObject->afterReconstitution();
		}

		return $adminSearchCollection;
	}
}