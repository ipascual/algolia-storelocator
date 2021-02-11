<?php

namespace BigHippo\StoreLocator\Controller\Adminhtml\Store;

class Delete extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'BigHippo_StoreLocator::storelocator';

    /**
     * @var \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface
     */
    protected $storeLocatorRepository;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface $storeLocatorRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \BigHippo\StoreLocator\Api\StoreLocatorRepositoryInterface $storeLocatorRepository
    ) {
        parent::__construct($context);
        $this->storeLocatorRepository = $storeLocatorRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($id) {
                $model = $this->storeLocatorRepository->get($id);
                $this->storeLocatorRepository->delete($model);
                $this->messageManager->addSuccessMessage(__('The Store has been deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('ID is required.'));
            }
        } catch (\Exception $ex) {
            $this->messageManager->addErrorMessage($ex->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
