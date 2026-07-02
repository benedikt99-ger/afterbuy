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
    protected $_sThisTemplate = '@bn_afterbuy/admin/ordertracking';
	
    public function render()
    {
		$result = "@bn_afterbuy/admin/ordertracking";
		parent::render();

		$orderId = $this->getEditObjectId();
        if ($orderId) {
            $order = oxNew(Order::class);
            if ($order->load($orderId)) {
                // $afterbuykdnr = $order->oxorder__oxbillnr->value;
                // $afterbuyuid  = $order->oxorder__oxtrackcode->value;

                // $this->addTplParam('afterbuykdnr', $afterbuykdnr);
                // $this->addTplParam('afterbuyuid', $afterbuyuid);
            }
        }
        return $result;
		// return parent::render();
    }

    public function saveData(): void
    {
    }
	
    public function getOrder(): string
    {
    }


}
