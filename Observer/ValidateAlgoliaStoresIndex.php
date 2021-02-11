<?php

namespace BigHippo\StoreLocator\Observer;

class ValidateAlgoliaStoresIndex implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Indexer\ConfigInterface
     */
    protected $indexerConfig;

    /**
     * @var \Magento\Framework\Indexer\IndexerInterfaceFactory
     */
    protected $indexerInterfaceFactory;

    /**
     * @var \BigHippo\StoreLocator\Model\Indexer\StoreLocator
     */
    protected $indexerStoreLocator;

    public function __construct(
        \Magento\Framework\Indexer\ConfigInterface $indexerConfig,
        \Magento\Framework\Indexer\IndexerInterfaceFactory $indexerInterfaceFactory,
        \BigHippo\StoreLocator\Model\Indexer\StoreLocator $indexerStoreLocator
    ) {
        $this->indexerConfig = $indexerConfig;
        $this->indexerInterfaceFactory = $indexerInterfaceFactory;
        $this->indexerStoreLocator = $indexerStoreLocator;
    }

    /**
     * @inheritDoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $model = $observer->getData('object');
        $algoliaStoresIndex = $this->indexerConfig->getIndexer('algolia_stores');
        if (isset($algoliaStoresIndex['indexer_id'])) {
            $indexer = $this->indexerInterfaceFactory->create()
                ->load($algoliaStoresIndex['indexer_id']);
            if ($indexer->isScheduled() && !$indexer->isInvalid()) {
                $indexer->invalidate();
            } elseif (!$indexer->isScheduled()) {
                $this->indexerStoreLocator->executeRow($model->getId());
            }
        }
    }
}
