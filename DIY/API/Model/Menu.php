<?php
namespace DIY\API\Model;

class Menu extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DIY\API\Model\ResourceModel\Menu');
    }


}
?>