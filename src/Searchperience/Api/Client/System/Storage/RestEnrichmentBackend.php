<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\Enrichment\Enrichment;
use Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection;
use Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment;
use Searchperience\Api\Client\Domain\Enrichment\MatchingRule;
use Searchperience\Api\Client\Domain\Enrichment\EnrichmentRepository;


/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestEnrichmentBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\EnrichmentBackendInterface {

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$arguments 	= $this->buildRequestArrayFromEnrichment($enrichment);
			$response 	= $this->executePostRequest($arguments);
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579269, $exception);
		}

		return $response->getStatusCode();
	}


	/**
	 * {@inheritdoc}
	 * @param int $id
	 * @return \Searchperience\Api\Client\Domain\Enrichment\Enrichment
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getById($id) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/enrichments/' . $id)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildEnrichmentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField = ''
	 * @param string $sortingType = desc
	 * @return \Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC) {
		$filterUrlString 	= $this->getFilterQueryString($filtersCollection);
		$sortingUrlString 	= $this->getSortingQueryString($sortingField, $sortingType);

		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/enrichments?start=' . $start . '&limit=' . $limit . $filterUrlString.$sortingUrlString)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		$xmlElement = $response->xml();

		return $this->buildEnrichmentsFromXml($xmlElement);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteById($id) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->delete('/{customerKey}/enrichments/' . $id)
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579284, $exception);
		}

		return $response->getStatusCode();
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Enrichment\Enrichment
	 */
	protected function buildEnrichmentFromXml(\SimpleXMLElement $xml) {
		$enrichments = $this->buildEnrichmentsFromXml($xml);
		return reset($enrichments);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection
	 */
	protected function buildEnrichmentsFromXml(\SimpleXMLElement $xml) {
		$enrichmentCollection = new EnrichmentCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$enrichmentCollection->setTotalCount((integer) $xml->totalCount->__toString());
		}

		$enrichments = $xml->xpath('enrichment');
		foreach($enrichments as $enrichment) {
			$enrichmentAttributeArray = (array)$enrichment->attributes();
			$enrichmentObject = new \Searchperience\Api\Client\Domain\Enrichment\Enrichment();
			$enrichmentObject->setId((integer) $enrichmentAttributeArray['@attributes']['id']);
			$enrichmentObject->setTitle((string)$enrichment->title);
			$enrichmentObject->setAddBoost((float) $enrichment->addBoost);
			$enrichmentObject->setDescription($enrichment->description);
			$enrichmentObject->setEnabled((bool) $enrichment->status);

			if(isset( $enrichment->matchingrules->matchingrule )) {
				foreach($enrichment->matchingrules->matchingrule as $matchingrule) {
					$matchingruleObject = new \Searchperience\Api\Client\Domain\Enrichment\MatchingRule();
					$matchingruleObject->setOperator($matchingrule->operator);
					$matchingruleObject->setFieldName($matchingrule->fieldName);
					$matchingruleObject->setOperatorValue($matchingrule->operatorValue);
					$enrichmentObject->addMatchingRule($matchingruleObject);
				}
			}

			if(isset( $enrichment->fieldenrichments->fieldenrichment )) {
				foreach($enrichment->fieldenrichments->fieldenrichment as $fieldenrichment ) {
					$fieldEnrichmentObject = new \Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment();
					$fieldEnrichmentObject->setFieldName((string) $fieldenrichment->fieldname);
					$fieldEnrichmentObject->setContent((string) $fieldenrichment->content);
					$enrichmentObject->addFieldEnrichment($fieldEnrichmentObject);
				}
			}

			$enrichmentCollection->append($enrichmentObject);
		}
		return $enrichmentCollection ;
	}

	/**
	 * Create an array containing only the available urlqueue property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment
	 * @return array
	 */
	protected function buildRequestArrayFromEnrichment(\Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment) {
		$valueArray = array();

		if (!is_null($enrichment->getId())) {
			$valueArray['id'] = $enrichment->getId();
		}

		if (!is_null($enrichment->getDescription())) {
			$valueArray['description'] = $enrichment->getDescription();
		}

		if (!is_null($enrichment->getEnabled())) {
			$valueArray['status'] = ($enrichment->getEnabled()) ? 1 : 0;
		}

		if (!is_null($enrichment->getTitle())) {
			$valueArray['title'] = $enrichment->getTitle();
		}

		if (!is_null($enrichment->getAddBoost())) {
			$valueArray['addBoost'] = $enrichment->getAddBoost();
		}

		if (!is_null($enrichment->getMatchingRulesCombinationType())) {
			$valueArray['matchingRuleCombinationType'] = $enrichment->getMatchingRulesCombinationType();
		}

		if (!is_null($enrichment->getMatchingRulesExpectedResult())) {
			$valueArray['matchingRuleExpectedResult'] = ($enrichment->getMatchingRulesExpectedResult()) ? 1 : 0;
		}

		foreach($enrichment->getFieldEnrichments() as $key => $fieldEnrichment) {
			/**
			 * @var $fieldEnrichment FieldEnrichment
			 */
			$data = array();
			$data['fieldName']		= $fieldEnrichment->getFieldName();
			$data['content']		= $fieldEnrichment->getContent();

			$valueArray['fieldEnrichments'][$key] = $data;
		}

		foreach($enrichment->getMatchingRules() as $key => $matchingRule) {
			/**
			 * @var $matchingRule MatchingRule
			 */
			$data = array();
			$data['fieldName'] 		= $matchingRule->getFieldName();
			$data['operator']		= $matchingRule->getOperator();
			$data['operatorValue']  = $matchingRule->getOperatorValue();

			$valueArray['matchingRules'][$key] = $data;
		}


		return $valueArray;
	}

	/**
	 * @param array $arguments
	 * @return \Guzzle\Http\Message\Response
	 */
	protected function executePostRequest(array $arguments) {
		$response = $this->restClient->setBaseUrl($this->baseUrl)
				->post('/{customerKey}/enrichments', NULL, $arguments)
				->setAuth($this->username, $this->password)
				->send();
		return $response;
	}

}
