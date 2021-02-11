<?php

namespace BigHippo\StoreLocator\Model\ResourceModel\StoreLocator;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(\BigHippo\StoreLocator\Model\StoreLocator::class, \BigHippo\StoreLocator\Model\ResourceModel\StoreLocator::class);
    }
}
