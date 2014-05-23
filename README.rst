++++++++++++++++++++++++
Searchperience Api Client
++++++++++++++++++++++++

:Author: Michael Klapper <michael.klapper@aoemedia.de>
:Author: AOE media <dev@aoemedia.com>
:Description: PHP Library to communicate with the searchperience RestFul API
:Homepage: http://www.searchperience.com
:Build status: |buildStatusIcon|


Installing via Composer
========================

The recommended way to install Searchperience API client is through [Composer](http://getcomposer.org).

1. Add ``aoemedia/searchperience-api-client`` as a dependency in your project's ``composer.json`` file:

::

	{
		"require": {
			"aoemedia/searchperience-api-client": "*"
		},
		"require-dev": {
			"guzzle/plugin-log": "*"
		}
	}

Consider tightening your dependencies to a known version when deploying mission critical applications (e.g. ``1.0.*``).

2. Download and install Composer:

::

	curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

::

	php composer.phar install

4. Require Composer's autoloader

Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

::

	require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at http://getcomposer.org.

Searchperience API Client basics
========================


Overview
-----------

The PHPApi client can be used to read and write entities from and to searchperience.
The single entrypoint in your code is in the idea case the Factory class, that is able to create all repositories with all dependencies:

You can use them in a static context:

\Searchperience\Common\Factory::get<Service>

will retrieve an instance of the repository that you want.

By now the following entities can be handles:

* DocumentRepository (Documents):

The most important entity that represents every crawled or imported documents.

* DocumentService:

Used to execute service operations on documents, like mark them for ReCrawling or ReIndexing

* EnrichmentRepository (Enrichments):

RuleSets that can be used to "attach" data or boosting to documents based on matching rules.
You can used them for example to attach searchterms to documents that do not contain them in there original data source.

* UrlQueueItemRepository (UrlQueueItems)

Queue of the crawler that contains urls that should be crawled next or can not be crawled because they throw errors
or are bloecked for some other reason.

* UrlQueueStatusRepository (UrlQueueStatus):

Status information about the urlqueue.


Add or update documents
-----------

::

	$document = new \Searchperience\Api\Client\Domain\Document\Document();
	$document->setContent('some content');
	$document->setForeignId(12);
	$document->setUrl('http://www.some.test/product/detail');

	$documentRepository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$documentRepository->add($document);

Get document from indexer
-----------

Get documents by foreign id

::

	$documentRepository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$document = $documentRepository->getByForeignId(12);


Get documents by query and filters

::

	$documentRepository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$document = $documentRepository->getAllByFilters(
		0,
		10,
		array(
			'crawl' => array(
				'crawlStart' => new DateTime(),
				'crawlEnd' =>  new DateTime()
			),
			'source' => array(
				'source' => 'magento'
			),
			'query' => array(
				'queryString' => 'test',
				'queryFields' => 'id,url'
			),
			'boostFactor' => array(
				'boostFactorEnd' => 123.00
			),
			'pageRank' => array(
				'pageRankStart' => 0.00,
				'pageRankEnd' => 123.00
			),
			'lastProcessed' => array(
				'processStart' =>  new DateTime(),
				'processEnd' =>  new DateTime()
			),
			'notifications' => array(
				'isduplicateof' => false,
				'lasterror' => true,
				'processingthreadid' => true
			)
		)
	);


::

Delete document from indexer
-----------

::

	$documentRepository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$documentRepository->deleteByForeignId(12);
::

Get the status of the document repository from searchperience.
------------

You can retrieve a status object with the searchperience api to get the amount of all documents, deleted, processed, processing and
document that have an error.

::

    $documentStatusRepository = \Searchperience\Common\Factory::getDocumentStatusRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$status = $documentStatusRepository->get();
    echo $status->getErrorCount();
::


UrlQueueItems
-----------

::

	$urlQueueItemRepository = \Searchperience\Common\Factory::getUrlQueueItemRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');
	$firstTen = $urlQueueItemRepository->getAllByFilters(0,10);
::

UrlQueueStatus
------------

::

    $urlQueueStatusRepository = \Searchperience\Common\Factory::getUrlQueueStatusRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');

    $status = $urlQueueStatusRepository->get();

    echo $status->getErrorCount();
::

The example above shows all documents that have an error.

Enrichments
------------


::

    $enrichmentRepository = \Searchperience\Common\Factory::getEnrichmentRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');

    $enrichment = new Enrichment();
    $enrichment->setTitle("test enrichment");

    $matchingRule = new MatchingRule();
    $matchingRule->setFieldname("brand_s");
    $matchingRule->setOperator(MatchingRule::OPERATOR_CONTAINS);
    $matchingRule->setOperandValue("aoe");

    $enrichment->addMatchingRule($matchingRule);

    $fieldEnrichment = new FieldEnrichment();
    $fieldEnrichment->setFieldName('highboost_words_sm');
    $fieldEnrichment->setContent('php');

    $enrichment->addFieldEnrichment($fieldEnrichment);
    $enrichment->setEnabled(true);

    $enrichmentRepository->add($enrichment);
::

The example above shows the creation of an enrichment for a document that contains "aoe" in the brand and adds "php"
as a word to the field "highboost_words_sm" that is configured as highly relevant for the search.


Synonyms
--------------

Sometimes it is usefull to replace searchterms with synonyms on index or searchtime.
In searchperience we provide an api to maintain these synonyms.

Depending on the project there can be multiple "instances" of synonym collections, to be able
to handle multiple usecases. Each of thes "instances" or "synonym collections" are represented by a tag.

To figure out which synonym instances exist you can use the SynonymTagRepository to get them:

::

    $synonymTagRepository = \Searchperience\Common\Factory::getSynonymTagRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');

    $allTags = $synonymTagRepository->getAll();

    foreach($allTags as $tag) {

        var_dump($tag->getTagName());
    }
::

When you push new Synonyms or Update existing once, you can instanciate a synonym object, with
mainWord, tag and words with the same meaning and push the,:

::

    $synonymRepository = \Searchperience\Common\Factory::getSynonymRepository('http://api.searchperience.com/', 'customerKey', 'username', 'password');

    $synonym = new \Searchperience\Api\Client\Domain\Synonym\Synonym();
    $synonym->setMainWord("bike");
    $synonym->setTagName("en");
    $synonym->addWordWithSameMeaning("bicycle");

    $synonymRepository->add($synonym);
::


Option requests
---------------
API provides self-descriptive interface by sending OPTIONS requests for any specified(valid) route:

::

    OPTIONS api.searchperience.me/###yourinstancename###


Example:

::

    OPTIONS http://demo:demo@api.searchperience.me/###yourinstancename###/documents

    <?xml version="1.0"?>
    <api>
        <add>
            <link href="documents?mimeType=_mime_&amp;amp;content=_content_&amp;amp;foreignId=_foreignId_&amp;amp;generalPriority=_generalPriority_&amp;amp;temporaryPriority=_temporaryPriority_&amp;amp;source=_source_&amp;amp;url=_url_&amp;amp;noIndex=_noIndex_&amp;amp;isProminent=_isProminent_&amp;amp;boostFactor=_boostFactor_" title="Adds a document"/>
        </add>
        <get>
            <link href="documents" title="Get all documents. Also here can be used additional filters like: 'query', 'crawlStart', 'crawlEnd', 'boostFactorStart', 'boostFactorEnd', 'pageRankStart', 'pageRankEnd', 'processStart', 'processEnd', 'isduplicateof', 'lasterror', 'processingthreadid', 'queryFields'"/>
            <link href="documents?foreignId=xyz" title="Get document by foreignId. Usually max 1 document should be in result collection"/>
            <link href="documents?url=http://www.url.de/" title="Get document by Url. Usually max 1 document should be in result collection"/>
        </get>
        <delete>
            <link href="documents?source=foo" title="deletes a document by source"/>
        </delete>
    </api>


Currently OPTIONS request supported by following routes:

- /###yourinstancename###/documents
- /###yourinstancename###/urlqueueitems
- /###yourinstancename###/enrichments
- /###yourinstancename###/status/urlqueue
- /###yourinstancename###/status/document

Trouble shooting
----------------
There is a HTTP_DEBUG mode which can be easy enabled.

::

	\Searchperience\Common\Factory::$HTTP_DEBUG = TRUE;



.. |buildStatusIcon| image:: https://secure.travis-ci.org/AOEpeople/searchperience-api-client.png?branch=master
   :alt: Build Status
   :target: http://travis-ci.org/AOEpeople/searchperience-api-client

