<?php

namespace Searchperience\Api\Client\Domain\AdminSearch;

/**
 * Repository to retrieve available AdminSearch objects from server.
 *
 * Class AdminSearchRepository
 *
 * @package Searchperience\Api\Client\Domain\AdminSearch
 * @author Michael Klapper <michael.klaper@aoe.com>
 */
class AdminSearchRepository {

	/**
	 * Retrieves all available AdminSearch objects registered for current connection.
	 *
	 * @return AdminSearchCollection
	 */
	public function getAll() {
		$adminSearchGerman = new AdminSearch();
		$adminSearchGerman->setTitle('German');
		$adminSearchGerman->setDescription('German search instance');
		$adminSearchGerman->setUrl('//bluestar.deploy.saascluster.aoe-works.de/index.php?id=1351');
		$adminSearchEnglish = new AdminSearch();
		$adminSearchEnglish->setTitle('English');
		$adminSearchEnglish->setDescription('English search instance');
		$adminSearchEnglish->setUrl('//bluestar.deploy.saascluster.aoe-works.de/index.php?id=1281');

		$adminSearchCollection = new AdminSearchCollection();
		$adminSearchCollection->append($adminSearchGerman);
		$adminSearchCollection->append($adminSearchEnglish);

		return $adminSearchCollection;
	}
}