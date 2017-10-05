<?php

namespace DIY\SimpleMenu\Controller\Adminhtml\menu;

use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{
    private $_apiMethods;

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        $this->_apiMethods = new SaveMethods();
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $isClause = $this->getRequest()->getParam("clause") != "" ? true : false;
        $editeclause = $this->getRequest()->getParam("editeclause") != "" ? true : false;

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('DIY\SimpleMenu\Model\Menu');

            $id = $this->getRequest()->getParam('item_id');
            if ($id) {
                $model->load($id);
            }

            /** Если мы пытаемся добавить Меню, то проверяем, есть ли уже menu_id, если нет -
             * сохраняем, иначе - не сохраняем, выдаём ошибку */
            if(!$isClause) {
                $menuIdSearch = $model->getCollection();
                $menuIdSearch->getSelect()
                    ->reset()
                    ->from(["s" => "simple_menu"], ["s.menu_id"])
                    ->where("s.menu_id=?", $data["menu_id"]);
                $resultSearch = $menuIdSearch->getData();

                if (!empty($resultSearch)) {
                    $this->messageManager->addErrorMessage(__('You try to add existing menu_id. Failed'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (!$isClause) {
                $data["parent_id"] = 0;
                $data["position"] = 1;
            } else {
                $data["parent_id"] = $this->getRequest()->getParam("clause");
            }

            /** Реализация перерасчёта позиций */
            if($this->_apiMethods->positionExist($this->getRequest()->getParam("clause"),$data["position"],$model)) {
                $this->_apiMethods->correctPositions($this->getRequest()->getParam("clause"),$data["position"],$model);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->_apiMethods->rewritePositionOrder($this->getRequest()->getParam("clause"),$data["position"],$model);

                if (!$isClause) {
                    $this->messageManager->addSuccess(__('Menu is created'));
                } else {
                    if ($editeclause) {
                        $this->messageManager->addSuccess(__('Menu item is edited'));
                    } else {
                        $this->messageManager->addSuccess(__('Menu item is created'));
                    }
                }
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['item_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Menu.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['item_id' => $this->getRequest()->getParam('item_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}