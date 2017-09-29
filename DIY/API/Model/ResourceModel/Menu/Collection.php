<?php

namespace DIY\API\Model\ResourceModel\Menu;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('DIY\API\Model\Menu', 'DIY\API\Model\ResourceModel\Menu');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>