<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * The Document object is used a a generic document type to push any kind of content.
 * You can use "setContent" to set the raw content and "setMimeType" to define the mimeType.
 *
 * Other document types have fixed mimeTypes and build the content in "getContent"
 *
 * @author Michael Klapper <michael.klapper@aoe.com>
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Document extends AbstractDocument {

	/**
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * Maximum content size is 3MB
	 *
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
}