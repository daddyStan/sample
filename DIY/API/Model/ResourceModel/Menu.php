<?php
namespace DIY\API\Model\ResourceModel;

class Menu extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('simple_menu', 'item_id');
    }
}
?>