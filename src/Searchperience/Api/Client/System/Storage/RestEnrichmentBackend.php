<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection;
use Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment;
use Searchperience\Api\Client\Domain\Enrichment\MatchingRule;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestEnrichmentBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\EnrichmentBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'enrichments';

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment) {
		return $this->getPostResponseFromEndpoint($enrichment);
	}

	/**
	 * {@inheritdoc}
	 * @param int $id
	 * @return \Searchperience\Api\Client\Domain\Enrichment\Enrichment|null
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getById($id) {
		try {
			$response = $this->getGetResponseFromEndpoint('/'.$id);
			return $this->buildEnrichmentFromXml($response->xml());
		} catch(EntityNotFoundException $e) {
			return null;
		}
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
		try {
			$response   = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new EnrichmentCollection();
		}

		return $this->buildEnrichmentsFromXml($xmlElement);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteById($id) {
		$response = $this->getDeleteResponseFromEndpoint('/'.$id);
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
			$enrichmentObject->__setProperty('id', (integer) $enrichmentAttributeArray['@attributes']['id']);
			$enrichmentObject->__setProperty('title', (string)$enrichment->title);
			$enrichmentObject->__setProperty('addBoost', (string) $enrichment->addBoost);
			$enrichmentObject->__setProperty('description', (string)$enrichment->description);
			$enrichmentObject->__setProperty('enabled', (bool)(int)$enrichment->status);

			if(isset( $enrichment->matchingrules->matchingrule )) {
				foreach($enrichment->matchingrules->matchingrule as $matchingrule) {
					$matchingRuleObject = new \Searchperience\Api\Client\Domain\Enrichment\MatchingRule();
					$matchingRuleObject->__setProperty('operator', (string)$matchingrule->operator);
					$matchingRuleObject->__setProperty('fieldName', (string)$matchingrule->fieldName);
					$matchingRuleObject->__setProperty('operandValue',(string)$matchingrule->operandValue);

					$matchingRules = $enrichmentObject->getMatchingRules();
					$matchingRules->append($matchingRuleObject);

					$matchingRuleObject->afterReconstitution();

					$enrichmentObject->__setProperty('matchingRules',$matchingRules);
				}
			}

			if(isset( $enrichment->fieldenrichments->fieldenrichment )) {
				foreach($enrichment->fieldenrichments->fieldenrichment as $fieldenrichment ) {
					$fieldEnrichmentObject = new \Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment();
					$fieldEnrichmentObject->__setProperty('fieldName',(string) $fieldenrichment->fieldName);
					$fieldEnrichmentObject->__setProperty('content',(string) $fieldenrichment->content);

					$fieldEnrichments = $enrichmentObject->getFieldEnrichments();
					$fieldEnrichments->append($fieldEnrichmentObject);

					$fieldEnrichmentObject->afterReconstitution();

					$enrichmentObject->__setProperty('fieldEnrichments',$fieldEnrichments);
				}
			}

            if(isset($enrichment->contextsBoosting->context)) {
                $contextsBoosting = $enrichment->contextsBoosting;
                $enrichmentObject->__setProperty('contextsBoosting', $contextsBoosting);
            }

			$enrichmentObject->afterReconstitution();
			$enrichmentCollection->append($enrichmentObject);
		}
		return $enrichmentCollection ;
	}

    /**
     * @param \Searchperience\Api\Client\Domain\AbstractEntity $enrichment
     * @return array
     */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity  $enrichment) {
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
			$data['operandValue']  = $matchingRule->getOperandValue();

			$valueArray['matchingRules'][$key] = $data;
		}

        if (!is_null($enrichment->getContextsBoosting())) {
            // return SimpleXMLElement object
            $json = json_encode($enrichment->getContextsBoosting());
            // converting to normal array
            $contexts = json_decode($json, TRUE);
            $contextsBoosting = ['contextsBoosting' => $contexts];
            $valueArray['contextsBoosting'] = $contextsBoosting;
        }

		return $valueArray;
	}
}
