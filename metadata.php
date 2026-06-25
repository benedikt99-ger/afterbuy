<?php
/* Please retain this copyright header in all versions of the software
 *
 * Copyright (C) 2017 Ivo Bathke
 *
 * It is published under the MIT Open Source License.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

$sMetadataVersion = '2.0';
$aModule          = [
    'id'          => \IvobaOxid\SplitCategoryDesc\Module::MODULE_ID,
    'title'       => 'Ivo Bathke: Split Category Desc',
    'description' => [
        'de' => 'Kategorie Text aufteilen mit einem ###more### token.',
        'en' => 'Split category text by adding a ###more### token.',
    ],
    'thumbnail'   => 'ivoba-oxid.png',
    'version'     => '2.1',
    'author'      => 'Ivo Bathke',
    'email'       => 'hello(at)ivo-bathke.name',
    'url'         => 'https://oxid.ivo-bathke.name#split-category-desc',
    'extend'      => [\OxidEsales\Eshop\Application\Model\Category::class => \IvobaOxid\SplitCategoryDesc\Application\Model\Category::class],
    'blocks'      => [
        [
            'template' => 'page/list/list.tpl',
            'block'    => 'page_list_listbody',
            'file'     => 'views/frontend/blocks/smarty/split_category_desc.tpl',
            'position' => 2 //in case of using another module (f.e ivoba-oxid/ivoba-manufacturer-description) that also extends this block we move it to later position
        ],
    ],
    'settings'    => [
        [
            'group' => 'ivoba_split_category_desc_main',
            'name'  => 'ivoba_split_category_desc_token',
            'type'  => 'str',
            'value' => '###more###',
        ],
    ],
];
