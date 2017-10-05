<?php
namespace DIY\SimpleMenu\Api;

/**
 * Интерфейс API
 */
interface ApiInterface
{
    /**
     * Метод обработки запроса REST API
     * @param int | string $parentId Left hand operand.
     * @return array array of menu.
     */
    public function getmenu(string $parentId);
}