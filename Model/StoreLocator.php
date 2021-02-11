<?php

namespace BigHippo\StoreLocator\Model;

class StoreLocator extends \Magento\Framework\Model\AbstractModel
    implements \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface, \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'store_locator';

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\StoreLocator::class);
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @inheritDoc
     */
    public function getStreet()
    {
        return $this->getData('street');
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->getData('city');
    }

    /**
     * @inheritDoc
     */
    public function getRegion()
    {
        return $this->getData('region');
    }

    /**
     * @inheritDoc
     */
    public function getPostcode()
    {
        return $this->getData('postcode');
    }

    /**
     * @inheritDoc
     */
    public function getCountry()
    {
        return $this->getData('country');
    }

    public function afterSave()
    {
        $this->_eventManager->dispatch('algolia_store_after_save', ['object' => $this]);
        return parent::afterSave();
    }

    public function afterDelete()
    {
        $this->_eventManager->dispatch('algolia_store_after_save', ['object' => $this]);
        return parent::afterSave();
    }
}
