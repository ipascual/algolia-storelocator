<?php

namespace BigHippo\StoreLocator\Model;

class StoreLocatorManagement
{
    /**
     * @var StoreLocatorRepository
     */
    protected $storeLocatorRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * StoreLocatorManagement constructor.
     * @param StoreLocatorRepository $storeLocatorRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \BigHippo\StoreLocator\Model\StoreLocatorRepository $storeLocatorRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param array|null $ids
     * @return array
     */
    public function getStoreLocations($ids = null)
    {
        $result = [];
        $storeLocations = $this->getStoreItems($ids);
        $idsToRemove = !empty($ids) ? array_flip($ids) : [];
        $result['toIndex'] = [];
        if ($storeLocations->getTotalCount()) {
            foreach ($storeLocations->getItems() as $store) {
                $result['toIndex'][] = [
                    'name' => $store->getName(),
                    'street' => $store->getStreet(),
                    'city' => $store->getCity(),
                    'region' => $store->getRegion(),
                    'objectID' => $store->getId(),
                ];
                if (isset($idsToRemove[$store->getId()])) {
                    unset($idsToRemove[$store->getId()]);
                }
            }
        }
        $result['toRemove'] = array_unique(array_keys($idsToRemove));

        return $result;
    }

    /**
     * @param array|null $ids
     * @return \BigHippo\StoreLocator\Api\Data\StoreLocatorSearchResultsInterface
     */
    protected function getStoreItems($ids = null)
    {
        if (!empty($ids)) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('id', $ids, 'in')
                ->create();
        } else {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        }
        return $this->storeLocatorRepository->getList($searchCriteria);
    }
}
