<?php

/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DIY\API\Model;

use DIY\API\Api\ApiInterface;
use Magento\Framework\App\Action\Context;
use DIY\API\Model\apiMethods;

/**
 * Defines the implementaiton class of the calculator service contract.
 */
class Api implements ApiInterface
{

    protected $_menuFactory;
    protected $_apiMethods;

    public function __construct(
        Context $context,
        \DIY\API\Model\MenuFactory $menuFactory,
        apiMethods $apiMethods
    )
    {
        $this->_menuFactory = $menuFactory;
        $this->_apiMethods = $apiMethods;
    }

    /**
     * Return the sum of the two numbers.
     *
     * @api
     * @param int $num1 Left hand operand.
     * @return int The sum of the two values.
     */
    public function getmenu($parentId) {
        $collection = $this->_menuFactory->create()->getCollection();
        $parentCollection = $this->_menuFactory->create()->getCollection();

        $collection->getSelect()
            ->reset()
            ->from(["s" => "simple_menu"],["s.item_id","s.name", "s.url", "s.position"])
            ->where("s.parent_id=?", $parentId);

        $parentCollection->getSelect()
            ->reset()
            ->from(["s" => "simple_menu"],["s.name","s.menu_id"])
            ->where("s.item_id=?", $parentId);

        $result = $parentCollection->getData();
        $result[0]["items"] = $collection->getData();

        return $result[0];
    }

}