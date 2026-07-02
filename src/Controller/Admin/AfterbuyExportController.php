<?php

declare(strict_types=1);

namespace nuenemann\AfterbuyExport\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Order as EshopOrder;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\Eshop\Application\Controller\Admin\AdminController;

/**
 * @eshopExtension
 *
 * This is an example for a module extension (chain extend) of
 * the shop start controller.
 * NOTE: class must not be final.
 */
class AfterbuyExportController extends AdminController
{

    protected $_sThisTemplate = '@bn_afterbuy/admin/afterbuyexport';
    public function render()
    {
        // $editRequest = $this->getServiceFromContainer(EditRequestInterface::class);
        // $factsSettings = $this->getServiceFromContainer(FactsSettingsInterface::class);
        // $this->addTplParam('measurementOptions', $factsSettings->getMeasurementOptions());
        // $this->addTplParam('additionalInformationOptions', $factsSettings->getAdditionalInformationOptions());
        // $productFacts = $factsService->getProductFacts($editRequest->getProductId());
        // $this->addTplParam('nutritionFacts', $productFacts->getNutritionFacts());
        return parent::render();
    }

    public function saveData(): void
    {
        // $editRequest = $this->getServiceFromContainer(EditRequestInterface::class);
        // $productFactsFactory = $this->getServiceFromContainer(ProductFactsFactoryInterface::class);
        // $factsService = $this->getServiceFromContainer(FactsServiceInterface::class);
        // $factsService->saveProductFacts($editRequest->getProductId(), $productFactsFactory->getFromRequest());
    }
	
    public function getOrder(): string
    {
        $order  = $this->getOrder();
        $result = $order;
        return $result;
    }


}
