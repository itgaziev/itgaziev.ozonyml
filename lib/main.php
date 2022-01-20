<?php

namespace ITGaziev\OzonYML;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\CIBlock;
use Bitrix\Main\Sale;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Catalog;

class Main {
    static $module_id = 'itgaziev.ozonyml';

    public static function getIBlockList() {
        if(!Loader::includeModule('iblock')) return array();

        $arrIBlockTypes = array();

        $res = \CIBlock::GetList([], [], true);
        while($ar_res = $res->Fetch()) {
            $arrIBlockTypes['REFERENCE'][] = '['.$ar_res['ID'] .'] ' . $ar_res['NAME'];
            $arrIBlockTypes['REFERENCE_ID'][] = $ar_res['ID'];
        }

        return $arrIBlockTypes;
    }

    public static function getAgentTime() {
        $listTime = [];

        $listTime['REFERENCE'][0] = 'Не выгружать';
        $listTime['REFERENCE_ID'][0] = 0;

        $listTime['REFERENCE'][1] = 'Каждый час';
        $listTime['REFERENCE_ID'][1] = 1;

        $listTime['REFERENCE'][2] = 'Каждые 3 часа';
        $listTime['REFERENCE_ID'][2] = 2;

        $listTime['REFERENCE'][3] = 'Каждый день';
        $listTime['REFERENCE_ID'][3] = 3;

        $listTime['REFERENCE'][4] = 'Раз в неделю';
        $listTime['REFERENCE_ID'][4] = 4;

        return $listTime;
    }

    public static function getFieldIblock() {
        $result[] = array('id' => 'ID', 'text' => 'ID');
        $result[] = array('id' => 'NAME', 'text' => 'Название');
        $result[] = array('id' => 'CODE', 'text' => 'Символьный код');
        $result[] = array('id' => 'PREVIEW_TEXT', 'text' => 'Короткое описание');
        $result[] = array('id' => 'DETAIL_TEXT', 'text' => 'Детальное описание');

        return $result;
    }

    public static function getProperties($iblock) {
        Loader::includeModule('iblock');
        $res = \CIBlock::GetProperties($iblock, array(), array());
        $result = [];

        while($res_ar = $res->Fetch()) {
            $result[] = array('id' => 'PROPERTY[' . $res_ar['CODE'] . ']', 'text' => '[' . $res_ar['ID' . '] ' . $res_ar['NAME']]);
        }

        return $result;
    }

    public static function getStores() {
        $resStore = \Bitrix\Catalog\StoreTable::getList([
            'select' => ['*'],
            'filter' => [
              'ACTIVE' => 'Y',
            ]
        ]);
        while($arStore = $resStore->fetch()) $arStores[] = $arStore;

        return $arStores;
    }

    public static function getArraysSelect($iblock) {
        $group = [
            'text' => '',
            'children' => [
                array('id' => 'self', 'text' => 'Свое значение')
            ]
        ];

        $result[] = $group;

        $group = [
            'text' => 'Поля инфоблока',
            'children' => self::getFieldIblock()
        ];

        $result[] = $group;

        return $result;
    }
}