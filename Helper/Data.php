<?php

namespace BigHippo\StoreLocator\Helper;

class Data
{
    const INDEX_SUFFIX = '_stores';

    /**
     * @var \Algolia\AlgoliaSearch\Helper\AlgoliaHelper
     */
    protected $algoliaHelper;

    /**
     * @var \Algolia\AlgoliaSearch\Helper\Logger
     */
    protected $logger;

    /**
     * @var \Algolia\AlgoliaSearch\Helper\Data
     */
    protected $helperData;

    /**
     * @var \BigHippo\StoreLocator\Model\StoreLocatorManagement
     */
    protected $storeLocatorManagement;

    /**
     * Data constructor.
     * @param \Algolia\AlgoliaSearch\Helper\AlgoliaHelper $algoliaHelper
     * @param \Algolia\AlgoliaSearch\Helper\Logger $logger
     * @param \Algolia\AlgoliaSearch\Helper\Data $helperData
     * @param \BigHippo\StoreLocator\Model\StoreLocatorManagement $storeLocatorManagement
     */
    public function __construct(
        \Algolia\AlgoliaSearch\Helper\AlgoliaHelper $algoliaHelper,
        \Algolia\AlgoliaSearch\Helper\Logger $logger,
        \Algolia\AlgoliaSearch\Helper\Data $helperData,
        \BigHippo\StoreLocator\Model\StoreLocatorManagement $storeLocatorManagement
    ) {
        $this->algoliaHelper = $algoliaHelper;
        $this->logger = $logger;
        $this->helperData = $helperData;
        $this->storeLocatorManagement = $storeLocatorManagement;
    }

    /**
     * @param int $storeId
     * @param array|null $storeLocatorIds
     * @throws \Algolia\AlgoliaSearch\Exceptions\AlgoliaException
     */
    public function rebuildStoreLocatorIndex($storeId, array $storeLocatorIds = null)
    {
        $indexName = $this->helperData->getIndexName(self::INDEX_SUFFIX, $storeId);

        $this->helperData->startEmulation($storeId);

        $storeLocations = $this->storeLocatorManagement->getStoreLocations($storeLocatorIds);

        $this->helperData->stopEmulation();

        $isFullReindex = (!$storeLocatorIds);

        if (!empty($storeLocations['toIndex'])) {
            $toIndexName = $indexName . ($isFullReindex ? '_tmp' : '');
            foreach (array_chunk($storeLocations['toIndex'], 100) as $chunk) {
                try {
                    $this->algoliaHelper->addObjects($chunk, $toIndexName);
                } catch (\Exception $e) {
                    $this->logger->log($e->getMessage());
                    continue;
                }
            }
        }

        if (!$isFullReindex && !empty($storeLocations['toRemove'])) {
            foreach (array_chunk($storeLocations['toRemove'], 100) as $chunk) {
                try {
                    $this->algoliaHelper->deleteObjects($chunk, $indexName);
                } catch (\Exception $e) {
                    $this->logger->log($e->getMessage());
                    continue;
                }
            }
        }

        if ($isFullReindex) {
            $this->algoliaHelper->copyQueryRules($indexName, $indexName . '_tmp');
            $this->algoliaHelper->moveIndex($indexName . '_tmp', $indexName);
        }

        $this->algoliaHelper->setSettings($indexName, $this->getIndexSettings());
    }

    /**
     * @return array
     */
    protected function getIndexSettings()
    {
        $indexSettings = [
            'searchableAttributes' => ['unordered(name)', 'unordered(street)', 'unordered(city)', 'unordered(region)'],
            'attributesToSnippet'  => ['street:7'],
        ];

        $transport = new \Magento\Framework\DataObject($indexSettings);
        $indexSettings = $transport->getData();

        return $indexSettings;
    }
}
