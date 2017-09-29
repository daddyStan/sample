<?php
namespace DIY\API\Controller\Adminhtml\menu;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;


class Save extends \Magento\Backend\App\Action
{

    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
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
        $isClause =  $this->getRequest()->getParam("clause") != "" ? true : false;
        $editeclause =  $this->getRequest()->getParam("editeclause") != "" ? true : false;

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('DIY\API\Model\Menu');

            $id = $this->getRequest()->getParam('item_id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }
			try{
				$uploader = $this->_objectManager->create(
					'Magento\MediaStorage\Model\File\Uploader',
					['fileId' => 'image']
				);
				$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
				/** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
				$imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				/** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
				$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
					->getDirectoryRead(DirectoryList::MEDIA);
				$result = $uploader->save($mediaDirectory->getAbsolutePath('emizen_banner'));
					if($result['error']==0)
					{
						$data['image'] = 'emizen_banner' . $result['file'];
					}
			} catch (\Exception $e) {
				//unset($data['image']);
            }
			//var_dump($data);die;
			if(isset($data['image']['delete']) && $data['image']['delete'] == '1')
				$data['image'] = '';

            if(!$isClause) {
                $data["parent_id"] = 0;
                $data["position"] = 1;
            } else {
                $data["parent_id"] = $this->getRequest()->getParam("clause");
            }

            $model->setData($data);

            try {
                $model->save();
                if(!$isClause) {
                    $this->messageManager->addSuccess(__('Меню создано'));
                } else {
                    if($editeclause) {
                        $this->messageManager->addSuccess(__('Пунтк меню отредактирован'));
                    } else {
                        $this->messageManager->addSuccess(__('Пунтк меню создан'));
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