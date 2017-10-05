<?php
/**
 * Created by PhpStorm.
 * User: koshpaevsv
 * Date: 03.10.17
 * Time: 17:20
 */

namespace DIY\SimpleMenu\Controller\Adminhtml\menu;
use Zend_Db_Expr;

class SaveMethods
{
    public function positionExist($parentId, $position, $model)
    {
        $select = $model->getCollection();
        $select->getSelect()
            ->reset()
            ->from(["s" => "simple_menu"], ["s.position"])
            ->where("s.position=?", $position)
            ->where("s.parent_id=?", $parentId);
        $resultSearch = $select->getData();
        if(!empty($resultSearch)) {
            return true;
        }
        return false;
    }

    public function correctPositions($parentId, $position, $model)
    {
        $select = $model->getCollection();
        $where[] = "position>=$position";
        $where[] = "parent_id = '$parentId'";
        $select->getConnection()->update("simple_menu",["position" => new Zend_Db_Expr('position + 1')],$where);

        return $position;
    }

    public function rewritePositionOrder($parentId, $position, $model)
    {
        $select = $model->getCollection();
        $select->getConnection()->query("UPDATE simple_menu set position=(select @a:= @a + 1 from (select @a:= 0) s) WHERE  parent_id = $parentId ORDER BY position ASC;");
    }

    /**
     * Получаем name родителя
     * @param $item_id item_id родителя
     * @return mixed | string Имя родителя
     */
    public function getItemName($item_id)
    {
        /** Создаём необходимое подключение к бд */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();

        /** получаем и фетчим имя родителя*/
        $sql = $connection->select()
            ->from(["s" => "simple_menu"],["s.name"])
            ->where('item_id = ?', $item_id);
        $result = $connection->fetchAll($sql);

        return $result[0]["name"];
    }
}