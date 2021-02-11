<?php

namespace BigHippo\StoreLocator\Model;

class StoreLocatorRepository implements \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface
{
    /**
     * @var StoreLocatorFactory
     */
    protected $storeLocatorFactory;

    /**
     * @var ResourceModel\StoreLocator
     */
    protected $resource;

    /**
     * @var ResourceModel\StoreLocator\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * StoreLocatorRepository constructor.
     *
     * @param StoreLocatorFactory $storeLocatorFactory
     * @param ResourceModel\StoreLocator $resource
     * @param ResourceModel\StoreLocator\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
     * @param \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        \BigHippo\StoreLocator\Model\StoreLocatorFactory $storeLocatorFactory,
        \BigHippo\StoreLocator\Model\ResourceModel\StoreLocator $resource,
        \BigHippo\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->storeLocatorFactory = $storeLocatorFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        /** @var StoreLocator $storeLocator */
        $storeLocator = $this->storeLocatorFactory->create();
        $storeLocator->load($id);
        if (!$storeLocator->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Store data with ID "%1" does not exist.', $id));
        }
        return $storeLocator;
    }

    /**
     * @inheritDoc
     */
    public function save(\BigHippo\StoreLocator\Api\Data\StoreLocatorInterface $storeLocator)
    {
        try {
            $this->resource->save($storeLocator);
            return $storeLocator;
        } catch (\Exception $ex) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Could not save the Store: %1', $ex->getMessage()),
                $ex
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete(\BigHippo\StoreLocator\Api\Data\StoreLocatorInterface $storeLocator)
    {
        try {
            $this->resource->delete($storeLocator);
            return true;
        } catch (\Exception $ex) {
            throw new \Magento\Framework\Exception\CouldNotDeleteException(
                __('Could not delete the Store: %1', $ex->getMessage()),
                $ex
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
