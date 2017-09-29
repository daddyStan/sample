<?php

namespace DIY\API\Block\Adminhtml\Menu\Edit;

/**
 * Adminhtml menu edit form block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $isClause =  $this->getRequest()->getParam("clause") != "" ? true : false;
        $editeclause =  $this->getRequest()->getParam("editeclause") != "" ? "/editeclause/1" : "";

        /** @var \Magento\Framework\Data\Form $form */
        if(!$isClause) {
            $form = $this->_formFactory->create(
                ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
            );
        } else {
            $form = $this->_formFactory->create(
                [
                    'data' => [
                        'id' => 'edit_form',
                        'action' => $this->getData('action') . "clause/" . $this->getRequest()->getParam("clause") . $editeclause,
                        'method' => 'post',
                        'enctype' => 'multipart/form-data'
                    ]
                ]
            );
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}