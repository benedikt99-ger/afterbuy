<?php

/**
 * Copyright © benedikt nünemann. All rights reserved.
 */

use benedikt99\afterbuy\Module;

$sMetadataVersion = '2.1';
/**
 * Module information
 */
$aModule = [
    'id'          =>  Module::MODULE_ID,
    'title'       => 'Export Order to afterbuy',
    'description' => 'Export Order to afterbuy',
    'thumbnail'   => 'bn_logo.png',
    'version'     => '0.2.1',
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
