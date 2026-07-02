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
		$orderId = $this->getEditObjectId();
		if (!$orderId) {
			return;
		}

		$trackingnumber = Registry::getRequest()->getRequestEscapedParameter('trackingnumber');

		$sLogfile = Registry::getConfig()->getLogsDir() .'bn.log';
		file_put_contents($sLogfile, trim(date('Y-m-d H:i:s')." saveData ".$trackingnumber ).PHP_EOL,FILE_APPEND);		


		$order = oxNew(Order::class);
		if ($order->load($orderId)) {
			$order->oxorder__oxtrackcode = new \OxidEsales\Eshop\Core\Field($trackingnumber, \OxidEsales\Eshop\Core\Field::T_RAW);
			$order->save();
		}		
    }
	
    public function sendTracking(): void
    {
		$orderId = $this->getEditObjectId();
		if (!$orderId) {
			return;
		}

		$trackingnumber = Registry::getRequest()->getRequestEscapedParameter('trackingnumber');

		$sLogfile = Registry::getConfig()->getLogsDir() .'bn.log';
		file_put_contents($sLogfile, trim(date('Y-m-d H:i:s')." sendTracking ".$trackingnumber ).PHP_EOL,FILE_APPEND);		

		$order = oxNew(Order::class);
		if ($order->load($orderId)) {
			
		}		
    }


}
