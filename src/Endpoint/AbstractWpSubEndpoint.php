<?php

namespace Vnn\WpApiClient\Endpoint;

/**
 * Class AbstractWpSubEndpoint.
 * Is the base class for endpoints that "have" a parent entity, e.g. post revisions.
 * The setParent() method must be called before the get() method.
 *
 * Example:
 * $client->postRevisions()->setParent(45)->get();
 *
 * @package Vnn\WpApiClient\Endpoint
 */
abstract class AbstractWpSubEndpoint extends AbstractWpEndpoint
{
    /**
     * The ID of the parent entity.
     * @var int
     */
    private $parentId = null;

    /**
     * Set the ID of the parent entity.
     * @param int $parent
     * @return AbstractWpEndpoint
     */
    public function setParent($parent)
    {
        $this->parentId = $parent;
        return $this;
    }

    /**
     * Builds the actual endpoint URL using the provided parent ID and the endpoint pattern.
     * @param string $pattern
     * @return string
     * @throws \LogicException
     */
    protected function buildEndpoint($pattern)
    {
        if ($this->parentId === null) {
            throw new \LogicException(static::class . '::setParent() not called!', 1539454211);
        }
        return sprintf($pattern, $this->parentId);
    }
}
