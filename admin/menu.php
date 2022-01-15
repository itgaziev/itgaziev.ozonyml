<?php

use Bitrix\Main\Localization\Loc;

$accessLevel = (string) $APPLICATION->GetGroupRight('itgaziev.ozonyml');
if($accessLevel > 'D') {
    Loc::loadMessages(__FILE__);

    $ozMenu = [
        'parent_menu' => 'global_menu_marketing',
        'section' => 'itgaziev_ozonyml',
        'sort' => 1000,
        'text' => Loc::getMessage("ITGAZIEV_OZONYML_MENU_MAIN"),
        'title' => Loc::getMessage("ITGAZIEV_OZONYML_MENU_MAIN"),
        'icon' => 'itgaziev_ozonyml_icon',
        'items_id' => 'itgaziev_ozonyml_main',
        'items' => [
            [
                'text' => Loc::getMessage("ITGAZIEV_OZONYML_MENU_PRICE"),
                'title' => Loc::getMessage("ITGAZIEV_OZONYML_MENU_PRICE"),
                'url' => 'itgaziev.ozonyml_price_list.php?lang='.LANGUAGE_ID,
                'more_url' => array(
                    'itgaziev.ozonyml_price_edit.php'
                )
            ]
        ],
    ];

    return $ozMenu;

} else {
    return false;
}