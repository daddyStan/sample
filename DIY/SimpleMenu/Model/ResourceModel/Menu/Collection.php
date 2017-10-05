<?php

namespace DIY\SimpleMenu\Model\ResourceModel\Menu;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DIY\SimpleMenu\Model\Menu', 'DIY\SimpleMenu\Model\ResourceModel\Menu');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>