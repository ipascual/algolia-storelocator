<?php

namespace BigHippo\StoreLocator\Controller\Adminhtml\Store;

class Save extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'BigHippo_StoreLocator::storelocator';

    /**
     * @var \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface
     */
    protected $storeLocatorRepository;

    /**
     * @var \BigHippo\StoreLocator\Api\Data\StoreLocatorInterfaceFactory
     */
    protected $storeLocatorFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface $storeLocatorRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface $storeLocatorRepository,
        \BigHippo\StoreLocator\Api\Data\StoreLocatorInterfaceFactory $storeLocatorFactory
    ) {
        parent::__construct($context);
        $this->storeLocatorRepository = $storeLocatorRepository;
        $this->storeLocatorFactory = $storeLocatorFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if (!empty($data['id'])) {
                $model = $this->storeLocatorRepository->get($data['id']);
            } else {
                $model = $this->storeLocatorFactory->create();
            }
            $model->setData([
                'id' => $model->getId(),
                'name' => $data['name'],
                'street' => $data['street'],
                'city' => $data['city'],
                'region' => $data['region'],
                'postcode' => $data['postcode'],
                'country' => $data['country'],
            ]);
            $this->storeLocatorRepository->save($model);
            $this->messageManager->addSuccessMessage(__('Store saved.'));
            return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } catch (\Exception $ex) {
            $this->messageManager->addErrorMessage($ex->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
