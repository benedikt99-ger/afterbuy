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
    'id'          => 'afterbuy',
    'title'       => 'afterbuy',
    'description' => 'Export order for afterbuy',
    'thumbnail'   => 'pictures/bn_logo.png',
    'version'     => '0.1.0',
    'author'      => 'Nünemann',
    'url'         => 'https://github.com/benedikt99-ger/afterbuy',
    'email'       => 'benedikt@nuenemann.de',
	'extend' => [
		\OxidEsales\Eshop\Application\Model\Order::class => \benedikt99-ger\afterbuy\Model\Order::class,
	],
];
