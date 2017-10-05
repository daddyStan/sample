<?php

namespace DIY\SimpleMenu\Controller\Adminhtml\menu;

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
        $resultPage->setActiveMenu('DIY_SimpleMenu::menu');
        $resultPage->addBreadcrumb(__('DIY'), __('DIY'));
        $resultPage->addBreadcrumb(__('Manage item'), __('Manage Menu'));
        $saveMethods= new SaveMethods();
        $item =  $this->getRequest()->getParam("clause");

        if($item == "") {
            $resultPage->getConfig()->getTitle()->prepend(__('Menu'));
        } else {
            $parentName = $saveMethods->getItemName($item);
            $resultPage->getConfig()->getTitle()->prepend(__("Menu items ($parentName)"));
        }

        return $resultPage;
    }
}
?>