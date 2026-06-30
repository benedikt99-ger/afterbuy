<?php

/**
 * Copyright © benedikt nünemann. All rights reserved.
 */

# use nuenemann\AfterbuyExport\Model\Order as BNOrder;

$sMetadataVersion = '2.1';
/**
 * Module information
 */
$aModule = [
    'id'          => \nuenemann\AfterbuyExport\Module::MODULE_ID,
    'title'       => 'Export Order to afterbuy',
    'description' => 'Export Order to afterbuy',
    'thumbnail'   => 'bn_logo.png',
    'version'     => '0.2.3',
    'author'      => 'Nünemann',
    'url'         => 'https://github.com/benedikt99-ger/afterbuy',
    'email'       => 'benedikt@nuenemann.de',
	'extend' => [
		\OxidEsales\Eshop\Application\Model\Order::class => \nuenemann\AfterbuyExport\Model\Order::class,
	],
    'settings' => [
        array('group' => 'afterbuy_api',  'name' => 'sPartnerid',    'type' => 'str', 'value' => ''),
        array('group' => 'afterbuy_api',  'name' => 'sPartnerPass',  'type' => 'str', 'value' => ''),
		array('group' => 'afterbuy_api',  'name' => 'sUserID',       'type' => 'str', 'value' => ''),
    ]
];
