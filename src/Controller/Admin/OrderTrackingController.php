<?php
declare(strict_types=1);
namespace nuenemann\AfterbuyExport\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class OrderTrackingController extends AdminController
{
    protected $_sThisTemplate = '@bn_afterbuy/admin/ordertracking';
	
    public function render()
    {
		$result = "@bn_afterbuy/admin/ordertracking";
		parent::render();

		$orderId = $this->getEditObjectId();
        if ($orderId) {
            $order = oxNew(Order::class);
            if ($order->load($orderId)) {
                $trackingnumber  = $order->oxorder__oxtrackcode->value;
                $this->addTplParam('trackingnumber', $trackingnumber);
            }
        }
        return $result;
		// return parent::render();
    }

    public function saveData(): void
    {
    }
	
    public function getOrderNr(): string
    {
    }


}
