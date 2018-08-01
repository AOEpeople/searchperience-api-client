<?php

namespace Searchperience\Api\Client\Domain\Settings;

use Searchperience\Api\Client\Domain\AbstractRepository;

class SettingsRepository extends AbstractRepository {

    /**
     * @return LanguageCollection
     */
    public function getLanguages() {
        return $this->storageBackend->getLanguages();
    }
}