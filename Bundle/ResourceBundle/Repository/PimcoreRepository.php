<?php

namespace CoreShop\Bundle\ResourceBundle\Repository;

use CoreShop\Component\Resource\Metadata\MetadataInterface;
use CoreShop\Component\Resource\Repository\PimcoreRepositoryInterface;

class PimcoreRepository implements PimcoreRepositoryInterface
{
    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     *
     * @todo: rename to getList
     */
    public function getListingClass()
    {
        $className = $this->metadata->getClass('model');

        return $className::getList();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        $className = $this->metadata->getClass('model');

        return $className::getById($id);
    }
}
