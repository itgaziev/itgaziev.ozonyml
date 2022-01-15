<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Localization\Loc;
use ITGaziev\OzonAPI;
use Bitrix\Main\Entity\Base;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$module_id = 'itgaziev.ozonyml';

Loader::includeModule($module_id);

Loc::loadMessages(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);

if($POST_RIGHT == 'D') $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

$sTableID = Base::getInstance('\ITGaziev\OzonYML\Table\ITGazievOzonYMLTable')->getDBTableName();

