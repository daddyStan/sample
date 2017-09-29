<?php

namespace DIY\API\Block\Adminhtml;

class Menu extends \Magento\Backend\Block\Widget\Container
{
    /**
     * @var string
     */
    protected $_template = 'menu/menu.phtml';

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Widget\Context $context,array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * Prepare button and grid
     *
     * @return \Magento\Catalog\Block\Adminhtml\Product
     */
    protected function _prepareLayout()
    {
        $item =  $this->getRequest()->getParam("clause");

        if($item != "") {

            $addButtonProps = [
                'id' => 'add_new_clause',
                'label' => __('Добавить новый пункт меню'),
                'class' => 'add',
                'button_class' => '',
                'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
                'options' => $this->_getAddButtonOptions(),
            ];
            $addButtonBackProps = [
                'id' => 'back',
                'label' => __('Back'),
                'class' => 'back',
                'button_class' => '',
                'onclick' => "setLocation('" . $this->getUrl('api/menu/index') . "')",
                'options' => $this->_getAddButtonOptions(),
            ];

        } else {
            $addButtonProps = [
                'id' => 'add_new',
                'label' => __('Добавить новое меню'),
                'class' => 'add',
                'button_class' => '',
                'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
                'options' => $this->_getAddButtonOptions(),
            ];
        }
		
        $item != "" ? $this->buttonList->add('', $addButtonBackProps) : false ;
        $this->buttonList->add('add_new', $addButtonProps);
		

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('DIY\API\Block\Adminhtml\Menu\Grid', 'diy.menu.grid')
        );
        return parent::_prepareLayout();
    }

    /**
     *
     *
     * @return array
     */
    protected function _getAddButtonOptions()
    {
        $item =  $this->getRequest()->getParam("clause");
        if($item != "") {
            $splitButtonOptions[] = [
                'label' => __('Добавить новый пункт меню'),
                'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
            ];
        } else {
            $splitButtonOptions[] = [
                'label' => __('Добавить новое меню'),
                'onclick' => "setLocation('" . $this->_getCreateUrl() . "')"
            ];
        }



        return $splitButtonOptions;
    }

    /**
     *
     *
     * @param string $type
     * @return string
     */
    protected function _getCreateUrl()
    {
        $clause =  $this->getRequest()->getParam("clause");

        if($clause == "") {
            return $this->getUrl(
                'api/*/new'
            );
        } else {
            return $this->getUrl(
                'api/*/new/clause/' . $clause
            );
        }
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

}