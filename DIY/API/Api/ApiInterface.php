<?php

/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DIY\API\Api;

use DIY\API\Api\Data\PointInterface;

/**
 * Defines the service contract for some simple maths functions. The purpose is
 * to demonstrate the definition of a simple web service, not that these
 * functions are really useful in practice. The function prototypes were therefore
 * selected to demonstrate different parameter and return values, not as a good
 * calculator design.
 */
interface ApiInterface
{
    /**
     * Return the sum of the two numbers.
     *
     * @api
     * @param int | string $parentId Left hand operand.
     * @return array The sum of the numbers.
     */
    public function getmenu($parentId);
}