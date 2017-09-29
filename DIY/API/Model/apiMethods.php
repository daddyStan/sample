<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 27.09.17
 * Time: 12:22
 */

namespace DIY\API\Model;

class apiMethods
{

    public function setIsChild($collection) {
        foreach ($collection as &$item)
        {
            $item['end'] = true;
            if($this->haveChildren($item,$collection)) {
                $item['end'] = false;
            }
        }

        return $collection;
    }

    public function haveChildren($item, $collection) {
        foreach ($collection as &$key)
        {
            if($item['item_id'] == $key['parent_id']) {
                return true;
            }
        }

        return false;
    }

    public function removeParentId($collection) {
        foreach ($collection as &$item)
        {
            unset($item['parent_id']);
        }

        return $collection;
    }

    public function getChildren($itemId,$rawCollection) {
        $collection = $rawCollection->getData();

        foreach ($collection as $key => $value)
        {
            if($value["parent_id"] != $itemId) {
                unset($collection[$key]);
            }
        }

        return $collection;
    }
}