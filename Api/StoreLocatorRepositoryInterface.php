<?php

namespace BigHippo\StoreLocator\Api;

/**
 * Interface StoreLocatorRepositoryInterface
 *
 * @api
 * @package BigHippo\StoreLocator\Api
 */
interface StoreLocatorRepositoryInterface
{
    /**
     * @param int $id
     * @return \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id);

    /**
     * @param \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface $storeLocator
     * @return \BigHippo\StoreLocator\Api\Data\StoreLocatorInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\BigHippo\StoreLocator\Api\Data\StoreLocatorInterface $storeLocator);

    /**
     * @param Data\StoreLocatorInterface $storeLocator
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\BigHippo\StoreLocator\Api\Data\StoreLocatorInterface $storeLocator);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
