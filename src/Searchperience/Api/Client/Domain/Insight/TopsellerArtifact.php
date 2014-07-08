<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:22
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain\Insight;


/**
 * Class TopsellerArtifact
 * @package Searchperience\Api\Client\Domain\Insight
 */
class TopsellerArtifact extends GenericArtifact {
	/**
	 * @var string
	 * @Assert\Length(min = 1, max = 255)
	 */
	protected $typeName = 'topseller';
}