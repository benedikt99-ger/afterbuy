<?php

declare(strict_types=1);

namespace nuenemann\AfterbuyExport\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Registry;

class AfterbuyExportController extends AdminDetailsController
{

    protected $_sThisTemplate = '@bn_afterbuy/admin/afterbuyexport';
    public function render()
    {
		$result = "@bn_afterbuy/admin/afterbuyexport";
		parent::render();

		$orderId = $this->getEditObjectId();
        if ($orderId) {
            $order = oxNew(Order::class);
            if ($order->load($orderId)) {
				$ordernr = $order->oxorder__oxordernr->value;
                $afterbuykdnr = $order->oxorder__oxbillnr->value;
                $afterbuyuid  = $order->oxorder__oxtrackcode->value;
				$this->addTplParam('ordernr', $ordernr);
                $this->addTplParam('afterbuykdnr', $afterbuykdnr);
                $this->addTplParam('afterbuyuid', $afterbuyuid);

				$sLogfile = Registry::getConfig()->getLogsDir() .'bn.log';
				file_put_contents($sLogfile, trim(date('Y-m-d H:i:s')." render ".$afterbuykdnr ).PHP_EOL,FILE_APPEND);				
            }
        }
        return $result;
		// return parent::render();
    }

    public function saveData(): void
    {
    }
	
}
