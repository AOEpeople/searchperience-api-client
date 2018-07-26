<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class AbstractRestBackend
 * @package Searchperience\Api\Client\System\Storage
 *
 * User: Michael Klapper
 * Date: 16.11.12
 * Time: 21:19
 */
abstract class AbstractRestBackend {

	const SORTING_ASC = 'ASC';

	const SORTING_DESC = 'DESC';

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var
	 */
	protected $password;

	/**
	 * Default is set to "http://api.searchperience.me/"
	 *
	 * @string
	 */
	protected $baseUrl = 'http://api.searchperience.me/';

	/**
	 * @var \Guzzle\Http\Client
	 */
	protected $restClient;

	/**
	 * @var \Searchperience\Api\Client\System\DateTime\DateTimeService
	 */
	protected $dateTimeService;

	/**
	 * @var array
	 */
	protected static $allowedSortings = array(self::SORTING_ASC, self::SORTING_DESC);

	/**
	 * @var string
	 */
	protected $endpoint = '';

	/**
	 * Set the username to access the api.
	 *
	 * @param string $username
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setUsername($username) {
		if (!is_string($username) || $username === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('username cannot be empty string.');
		}
		$this->username = $username;
	}

	/**
	 * Set the password to access the api.
	 *
	 * @param string $password
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setPassword($password) {
		if (!is_string($password) || $password === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('password cannot be empty string.');
		}
		$this->password = $password;
	}

	/**
	 * Set the api base url including the customer path key.
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/bosch/
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setBaseUrl($baseUrl) {
		if (!is_string($baseUrl) || $baseUrl === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('baseUrl cannot be empty string.');
		}
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @param \Guzzle\Http\Exception\ClientErrorResponseException $exception
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return void
	 */
	protected function transformStatusCodeToClientErrorResponseException(\Guzzle\Http\Exception\ClientErrorResponseException $exception) {

		switch ($exception->getResponse()->getStatusCode()) {
			case 401:

				$e = new \Searchperience\Common\Http\Exception\UnauthorizedException($exception->getMessage(), 1353574907, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			case 403:
				$e = new \Searchperience\Common\Http\Exception\ForbiddenException($exception->getMessage(), 1353574915, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			case 404:
				$e = new \Searchperience\Common\Http\Exception\EntityNotFoundException($exception->getMessage(), 1353574919, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			case 405:
				$e = new \Searchperience\Common\Http\Exception\MethodNotAllowedException($exception->getMessage(), 1353574923, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			case 413:
				$e = new \Searchperience\Common\Http\Exception\RequestEntityTooLargeException($exception->getMessage(), 1353574956, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			default:
				$e = new \Searchperience\Common\Http\Exception\ClientErrorResponseException($exception->getMessage(), 1353574962, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
		}
	}

	/**
	 * @param \Guzzle\Http\Exception\ServerErrorResponseException $exception
	 *
	 * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @return void
	 */
	protected function transformStatusCodeToServerErrorResponseException(\Guzzle\Http\Exception\ServerErrorResponseException $exception) {

		switch ($exception->getResponse()->getStatusCode()) {
			case 500:
				$e = new \Searchperience\Common\Http\Exception\InternalServerErrorException($exception->getMessage(), 1353574974, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;
				break;

			default:
				$e = new \Searchperience\Common\Http\Exception\ServerErrorResponseException($exception->getMessage(), 1353574979, $exception);
				$e->setResponse($exception->getResponse());
				throw $e;

		}
	}

	/**
	 * @param \Guzzle\Http\Client $restClient
	 * @return void
	 */
	public function injectRestClient(\Guzzle\Http\Client $restClient) {
		$this->restClient = $restClient->setDefaultHeaders(array(
				'User-Agent' => 'Searchperience-API-Client version: ' . \Searchperience\Common\Version::Version,
				'Accepts' => 'application/searchperienceproduct+xml,application/xml,text/xml',
		));
	}

	/**
	 * @param \Searchperience\Api\Client\System\DateTime\DateTimeService $dateTimeService
	 * @return void
	 */
	public function injectDateTimeService(\Searchperience\Api\Client\System\DateTime\DateTimeService $dateTimeService) {
		$this->dateTimeService = $dateTimeService;
	}

	/**
	 * @param string $sorting
	 * @return boolean
	 */
	public static function getIsAllowedSorting($sorting) {
		return in_array($sorting, self::$allowedSortings);
	}

	/**
	 * @param string $sortingField
	 * @param string $sortingType
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return string
	 */
	protected function getSortingQueryString($sortingField  = '', $sortingType = self::SORTING_DESC) {
		if(trim($sortingField) == '') {
			return '';
		}

		if(!$this->getIsAllowedSorting($sortingType)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Invalid sorting passed');
		}

		return '&sortingField='.$sortingField.'&sortingType='.$sortingType;
	}


	/**
	 * @param $filtersCollection
	 * @return string
	 */
	protected function getFilterQueryString($filtersCollection) {
		$filterUrlString = '';

		if ($filtersCollection != null) {
			$filterUrlString = $filtersCollection->getFilterStringFromAll();
			return $filterUrlString;
		}
		return $filterUrlString;
	}

	/**
	 * @param string $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection|null $filtersCollection
	 * @param string $sortingField
	 * @param string $sortingType
	 * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Guzzle\http\Message\Response
	 */
	protected function getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType) {
		$filterUrlString = $this->getFilterQueryString($filtersCollection);
		$sortingUrlString = $this->getSortingQueryString($sortingField, $sortingType);

		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->get('/{customerKey}/'.$this->endpoint.'?start=' . $start . '&limit=' . $limit . $filterUrlString . $sortingUrlString)
				->setAuth($this->username, $this->password)
				->send();
			return $response;
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
            file_put_contents("/tmp/shit.log", $exception->getMessage() , FILE_APPEND);
			$this->transformStatusCodeToClientErrorResponseException($exception);
			return $response;
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
            file_put_contents("/tmp/shit.log", $exception->getMessage() , FILE_APPEND);
			$this->transformStatusCodeToServerErrorResponseException($exception);
			return $response;
		} catch (\Exception $exception) {
            file_put_contents("/tmp/shit.log", $exception->getMessage() , FILE_APPEND);
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $response;
	}

	/**
	 * @param object $entity
	 * @param string $queryString
	 * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @internal param string $endpoint
	 * @return int
	 */
	protected function getPostResponseFromEndpoint($entity, $queryString = '') {
		return $this->getPostResponseFromEndpointWithArray($this->buildRequestArray($entity), $queryString);
	}

    /**
     * @param string $queryString
     * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Exception\RuntimeException
     * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @internal param string $endpoint
     * @return int
     */
    protected function getPostResponseFromEndpointWithoutBody($queryString = '') {
        return $this->getPostResponseFromEndpointWithArray(array(), $queryString);
    }

    /**
     * @param array $postArray
     * @param string $queryString
     * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Exception\RuntimeException
     * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @internal param string $endpoint
     * @return int
     */
    private function getPostResponseFromEndpointWithArray($postArray, $queryString = '') {
        try {
            /** @var $response \Guzzle\http\Message\Response */
            $response = $this->executePostRequest($postArray, $queryString);
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
	 * @param string $postArray
	 * @param string $queryString
	 * @throws \Guzzle\Common\Exception\InvalidArgumentException
	 * @internal param $endpoint
	 * @return \Guzzle\Http\Message\Response
	 */
	protected function executePostRequest($postArray, $queryString = '') {
		$response = $this->restClient->setBaseUrl($this->baseUrl)
			->post('/{customerKey}/' . $this->endpoint . $queryString, NULL, $postArray)
			->setAuth($this->username, $this->password)
			->send();

		return $response;
	}

	/**
	 * Should be implemented by the document backend to get an post array from a domain object.
	 *
	 * @param \Searchperience\Api\Client\Domain\AbstractEntity $object
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $object) {
		return array();
	}

	/**
	 * @param string $queryString
	 * @return \Guzzle\http\Message\Response
	 * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 */
	protected function getGetResponseFromEndpoint($queryString = '') {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->get('/{customerKey}/'.$this->endpoint.$queryString)
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			var_dump($exception->getMessage());
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $response;
	}

	/**
	 * @param string $queryString
	 * @param AbstractEntity $entity
	 * @return \Guzzle\http\Message\Response
	 * @throws \Searchperience\Common\Http\Exception\EntityNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @throws \Searchperience\Common\Http\Exception\ServerErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 */
	protected function getDeleteResponseFromEndpoint($queryString = '', $entity = NULL) {
		try {
			$postArray = $entity !== NULL ? $this->buildRequestArray($entity) : array();
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->delete('/{customerKey}/'.$this->endpoint.$queryString, NULL, $postArray)
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579284, $exception);
		}

		return $response;
	}
}
