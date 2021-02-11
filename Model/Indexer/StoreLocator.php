<?php

namespace BigHippo\StoreLocator\Model\Indexer;

class StoreLocator implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var \Algolia\AlgoliaSearch\Helper\ConfigHelper
     */
    protected $configHelper;

    /**
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     */
    protected $consoleOutput;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Algolia\AlgoliaSearch\Helper\Data
     */
    protected $helper;

    /**
     * @var \Algolia\AlgoliaSearch\Model\Queue
     */
    protected $queue;

    /**
     * @var \BigHippo\StoreLocator\Helper\Data
     */
    protected $storeLocatorHelper;

    /**
     * StoreLocator constructor.
     * @param \Algolia\AlgoliaSearch\Helper\ConfigHelper $configHelper
     * @param \Symfony\Component\Console\Output\ConsoleOutput $consoleOutput
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Algolia\AlgoliaSearch\Helper\Data $helper
     * @param \Algolia\AlgoliaSearch\Model\Queue $queue
     * @param \BigHippo\StoreLocator\Helper\Data $storeLocatorHelper
     */
    public function __construct(
        \Algolia\AlgoliaSearch\Helper\ConfigHelper $configHelper,
        \Symfony\Component\Console\Output\ConsoleOutput $consoleOutput,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Algolia\AlgoliaSearch\Helper\Data $helper,
        \Algolia\AlgoliaSearch\Model\Queue $queue,
        \BigHippo\StoreLocator\Helper\Data $storeLocatorHelper
    ) {
        $this->configHelper = $configHelper;
        $this->consoleOutput = $consoleOutput;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->queue = $queue;
        $this->storeLocatorHelper = $storeLocatorHelper;
    }

    /**
     * @inheritDoc
     */
    public function executeFull()
    {
        $this->execute(null);
    }

    /**
     * @inheritDoc
     */
    public function executeList(array $ids)
    {
        $this->execute($ids);
    }

    /**
     * @inheritDoc
     */
    public function executeRow($id)
    {
        $this->execute([$id]);
    }

    /**
     * @inheritDoc
     */
    public function execute($ids)
    {
        if (!$this->configHelper->getApplicationID() || !$this->configHelper->getAPIKey() || !$this->configHelper->getSearchOnlyAPIKey()) {
            $errorMessage = 'Algolia reindexing failed: You need to configure your Algolia credentials in Stores > Configuration > Algolia Search.';
            if (php_sapi_name() === 'cli') {
                $this->consoleOutput->writeln($errorMessage);
            }
            return;
        }

        $storeIds = array_keys($this->storeManager->getStores());

        foreach ($storeIds as $storeId) {
            if (!$this->helper->isIndexingEnabled($storeId)) {
                continue;
            }

            $data = ['store_id' => $storeId];
            if (is_array($ids) && count($ids) > 0) {
                $data['store_locator_ids'] = $ids;
            }

            $this->queue->addToQueue(
                $this->storeLocatorHelper,
                'rebuildStoreLocatorIndex',
                $data,
                is_array($ids) ? count($ids) : 1
            );
        }
    }
}
