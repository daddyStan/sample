<?php
namespace DIY\SimpleMenu\Model;

class Menu extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DIY\SimpleMenu\Model\ResourceModel\Menu');
    }
}
?>