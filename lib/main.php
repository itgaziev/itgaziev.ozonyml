<?php

namespace ITGaziev\OzonYML;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\CIBlock;
use Bitrix\Main\Sale;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

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
}