<?php

namespace BigHippo\StoreLocator\Observer;

class AddStoreLocatorToConfig implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @inheritDoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $configuration = $observer->getData('configuration');
        $autocomplete = $configuration->getData('autocomplete');
        if (isset($autocomplete['sections'])) {
            $autocomplete['sections'][] = [
                'name' => 'stores',
                'label' => 'Store Locator',
                'hitsPerPage' => 3,
            ];
            $configuration->setData('autocomplete', $autocomplete);
        }
    }
}
