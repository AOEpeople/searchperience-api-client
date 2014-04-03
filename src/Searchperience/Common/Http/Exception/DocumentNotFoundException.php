<?php

namespace Searchperience\Common\Http\Exception;

/**
 * Exception when a client error is encountered (4xx codes)
 * @deprecated Please use entity not found exception since Document is reserved for documents in the domain layer
 */
class DocumentNotFoundException extends \Searchperience\Common\Http\Exception\ClientErrorResponseException {
}
