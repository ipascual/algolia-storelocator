<?php

namespace BigHippo\StoreLocator\Model\ResourceModel;

class StoreLocator extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init('bighippo_store_locator', 'id');
    }
}
