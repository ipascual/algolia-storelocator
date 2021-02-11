<?php

namespace BigHippo\StoreLocator\Api\Data;

/**
 * Interface StoreLocatorSearchResultsInterface
 *
 * @api
 * @package BigHippo\StoreLocator\Api\Data
 */
interface StoreLocatorSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface[]
     */
    public function getItems();

    /**
     * @param \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
