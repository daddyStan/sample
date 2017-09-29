<?php

namespace DIY\API\Controller\Adminhtml\menu;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPagee;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('DIY_API::menu');
        $resultPage->addBreadcrumb(__('DIY'), __('DIY'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Manage Menu'));

        $item =  $this->getRequest()->getParam("clause");
        if($item == "") {
            $resultPage->getConfig()->getTitle()->prepend(__('Управление меню'));
        } else {
            $resultPage->getConfig()->getTitle()->prepend(__('Управление пунктами меню'));
        }

        return $resultPage;
    }
}
?>