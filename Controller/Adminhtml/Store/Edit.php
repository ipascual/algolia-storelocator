<?php

namespace BigHippo\StoreLocator\Controller\Adminhtml\Store;

class Edit extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'BigHippo_StoreLocator::storelocator';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('BigHippo_StoreLocator::storelocator');
        $resultPage->addBreadcrumb(__('Store Locator'), __('Store Locator'));
        $resultPage->addBreadcrumb(__('Edit Store'), __('Edit Store'));
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Store'));

        return $resultPage;
    }
}
