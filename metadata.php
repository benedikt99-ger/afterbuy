<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => \benedikt99\afterbuy\Module::MODULE_ID,
    'title'       => 'Export Order to afterbuy',
    'description' => 'Export Order to afterbuy',
    'thumbnail'   => 'pictures/bn_logo.png',
    'version'     => '0.2.0',
    'author'      => 'Nünemann',
    'url'         => 'https://github.com/benedikt99-ger/afterbuy',
    'email'       => 'benedikt@nuenemann.de',
	'extend' => [
		\OxidEsales\Eshop\Application\Model\Order::class => \benedikt99\afterbuy\Model\Order::class,
	],
    'settings' => [
        array('group' => 'afterbuy_api',  'name' => 'sPartnerid',    'type' => 'str', 'value' => ''),
        array('group' => 'afterbuy_api',  'name' => 'sPartnerPass',  'type' => 'str', 'value' => ''),
		array('group' => 'afterbuy_api',  'name' => 'sUserID',       'type' => 'str', 'value' => ''),
    ]
];
