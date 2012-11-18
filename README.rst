++++++++++++++++++++++++
Searchperience Api Client
++++++++++++++++++++++++

:Author: Michael Klapper <michael.klapper@aoemedia.de>
:Author: AOE media <dev@aoemedia.com>
:Version: dev-master
:Description: PHP Library to communicate with the searchperience RestFul API
:Homepage: http://www.searchperience.com


Installing via Composer
========================

The recommended way to install Searchperience API client is through [Composer](http://getcomposer.org).

1. Add ``aoemedia/searchperience-api-client`` as a dependency in your project's ``composer.json`` file:

::

	{
		"require": {
			"aoemedia/searchperience-api-client": "*"
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

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

Searchperience API Client basics
========================

Add new documents to the indexer
-----------

::

	$document = new \Searchperience\Api\Client\Domain\Document();
	$document->setContent('some content');
	$document->setForeignId(12);
	$document->setUrl('http://www.some.test/product/detail');

	$documentRepsository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/qvc/', 'username', 'password');
	$documentRepsository->add($document);

Get document from indexer
-----------

::

	$documentRepsository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/qvc/', 'username', 'password');
	$document = $documentRepsository->getByForeignId(12);

Delete document from indexer
-----------

::

	$documentRepsository = \Searchperience\Common\Factory::getDocumentRepository('http://api.searchperience.com/qvc/', 'username', 'password');
	$documentRepsository->deleteByForeignId(12);

