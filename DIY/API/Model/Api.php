<?php

namespace DIY\API\Model;
use DIY\API\Api\ApiInterface;
use Magento\Framework\App\Action\Context;

/**
 * Класс обработки запроса REST API
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
     * Метод обработки запроса REST API
     * @param int | string $parentId Left hand operand.
     * @return array array of menu.
     */
    public function getmenu(string $parentId) {
        /** Определяем, что будем искать в зависимости от типа данных parantId*/
        $parentParam = is_numeric($parentId) ? "item_id" : "menu_id";

        /** Объявляем две фабричных коллекции */
        $collection = $this->_menuFactory->create()->getCollection();
        $parentCollection = $this->_menuFactory->create()->getCollection();

        /** Получаем объект для вывода родителя */
        $parentCollection->getSelect()
            ->reset()
            ->from(["s" => "simple_menu"],["s.item_id","s.name","s.menu_id"])
            ->where("s.$parentParam=?", $parentId);
        $result = $parentCollection->getData();

        /** Проверяем, нашли ли родителя */
        if($result) {
            /** Получаем объект для вывода детей */
            $collection->getSelect()
                ->reset()
                ->from(["s" => "simple_menu"], ["s.item_id", "s.name", "s.url"])
                ->where("s.parent_id=?", $result[0]["item_id"]);

            $result[0]["items"] = $collection->getData();
        } else {
            $result = [];
        }

        return $result;
    }
}