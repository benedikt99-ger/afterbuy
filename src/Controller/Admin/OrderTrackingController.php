<?php

declare(strict_types=1);

namespace nuenemann\AfterbuyExport\Controller\Admin;
// use nuenemann\AfterbuyExport\ServiceContainer;
use OxidEsales\Eshop\Application\Model\Order as EshopOrder;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

class OrderTrackingController extends AdminController
{
	// use ServiceContainer;
    protected $_sThisTemplate = '@bn_afterbuy/admin/afterbuyexport';
    public function render()
    {

        return parent::render();
    }

    public function saveData(): void
    {
    }

}
