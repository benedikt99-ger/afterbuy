<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ModuleTemplate\Controller;

use OxidEsales\Eshop\Application\Model\Order as EshopOrder;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop start controller.
 * NOTE: class must not be final.
 */
class OrderController extends OrderController_parent
{

    /**
     * All we need here is to fetch the information we need from a service.
     * As in our example we extend a block of a template belonging ONLY
     * to the shop's StartController, we extend that Controller with a new method.
     * NOTE: only leaf classes can be extended this way. The FrontendController class which
     *      many Controllers inherit from cannot be extended this way.
     */
    public function getOrder(): string
    {
        $order  = $this->getOrder();
        $result = $order;
        return $result;
    }


}
