<?php
namespace DIY\SimpleMenu\Block\Adminhtml\Menu\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('menu_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Menu Information'));
    }
}