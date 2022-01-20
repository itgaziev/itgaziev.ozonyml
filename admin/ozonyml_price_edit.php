<?php
use ITGaziev\OzonYML;
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Main\Loader::includeModule('itgaziev.ozonyml');

Loc::loadMessages(__FILE__);

$arJsConfig = array(
    //TODO : add js / css to header
    'jquery-ui' => array(
        'js' => '/bitrix/themes/.default/itgaziev.ozonyml/js/jquery-ui.min.js',
        'css' => '/bitrix/themes/.default/itgaziev.ozonyml/css/jquery-ui.min.css',
        'rel' => array()
    ),
    'jquery-ui-theme' => array(
        'css' => '/bitrix/themes/.default/itgaziev.ozonyml/css/jquery-ui.theme.min.css',
        'rel' => array()
    ),
    'jquery-select2' => array(
        'css' => '/bitrix/themes/.default/itgaziev.ozonyml/select2/css/select2.min.css',
        'js' => '/bitrix/modules/itgaziev.ozonyml/install/themes/.default/itgaziev.ozonyml/select2/js/select2.js?v=' . time(),
        //'js' => '/bitrix/themes/.default/itgaziev.ozonyml/select2/js/select2.js?v=' . time(),
        'rel' => array()
    ),
    'itgaziev.ozonyml' => array(
        'js' => '/bitrix/js/itgaziev.ozonyml/main.js',
        'css' => '/bitrix/modules/itgaziev.ozonyml/install/themes/.default/itgaziev.ozonyml.css',
        'rel' => array()
    ),
);

foreach($arJsConfig as $ext => $arExt) \CJSCore::RegisterExt($ext, $arExt);

CJSCore::Init(array('jquery'));
if($arJsConfig) {
    CUtil::InitJSCore(array_keys($arJsConfig));
}

$POST_RIGHT = $APPLICATION->GetGroupRight('itgaziev.ozonyml');

if($POST_RIGHT == 'D') $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

$aTabs = array(
    array(
        'DIV' => 'edit0',
        'TAB' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB0'),
        'ICON' => 'main_user_edit',
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB0_TITLE')
    ),
    array(
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB1'),
        'ICON' => 'main_user_edit',
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB1_TITLE')
    ),
    array(
        'DIV' => 'edit2',
        'TAB' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB2'),
        'ICON' => 'main_user_edit',
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TAB2_TITLE')
    )
);

if($ID > 0) {
    $result = OzonYML\Table\ITGazievOzonYMLTable::getById($ID);
    $condition = $result->fetch();
    if(!empty($condition['PARAMETERS'])) $condition['PARAMETERS'] = unserialize($condition['PARAMETERS']);
    if(!empty($condition['FILTERS'])) $condition['FILTERS'] = unserialize($condition['FILTERS']);

    //echo '<pre>'; print_r($condition['PARAMETERS']); echo '</pre>';
}

$tabControl = new CAdminTabControl('tabControl', $aTabs, false);

$tabAction = isset($_GET['tabControl_active_tab']) && !empty($_GET['tabControl_active_tab']) ? $_GET['tabControl_active_tab'] : 'edit0';

if($next != "") {
    if($REQUEST_METHOD == 'POST' && $POST_RIGHT == 'W' && check_bitrix_sessid()) {
        if($tabControl_active_tab == 'edit0' || empty($tabControl_active_tab)) {
            $arFields = array(
                'ACTIVE'        => $_POST['ACTIVE'],
                'NAME'          => $_POST['NAME'],
                'IBLOCK'        => $_POST['IBLOCK'],
                'AGENT_TIME'    => $_POST['AGENT_TIME'],
            );

            if($ID > 0) {
                //TODO : Update
                $result = OzonYML\Table\ITGazievOzonYMLTable::update($ID, $arFields);
                if($result->isSuccess()) $res = true;
                else {
                    $errors = $result->getErrorMessages();
                    $res = false;
                }
            } else {
                //TODO : Add
                $arFields['TIME_CREATE'] = new \Bitrix\Main\Type\DateTime();
                $result = OzonYML\Table\ITGazievOzonYMLTable::add($arFields);
                if($result->isSuccess()) {
                    $ID = $result->getID();
                    $res = true;
                } else {
                    $errors = $result->getErrorMessages();
                    $res = false;
                }
            }
        } else if($tabControl_active_tab == 'edit1' && $ID > 0) {
            // TODO : Update / save paramters
            if($ID > 0) {
                $arFields['PARAMETERS'] = serialize($_POST['PARAMETERS']);
                $result = OzonYML\Table\ITGazievOzonYMLTable::update($ID, $arFields);
                if($result->isSuccess()) $res = true;
                else {
                    $errors = $result->getErrorMessages();
                    $res = false;
                }
            }

        } else if($tabControl_active_tab == 'edit2' && $ID > 0) {

        }

        if($res && $ID) {
            if($tabControl_active_tab == 'edit0') {
                LocalRedirect("/bitrix/admin/itgaziev.ozonyml_price_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&tabControl_active_tab=edit1");
            } else if($tabControl_active_tab == 'edit1') {
                LocalRedirect("/bitrix/admin/itgaziev.ozonyml_price_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&tabControl_active_tab=edit2");
            } else if($tabControl_active_tab == 'edit2') {
                // TODO : Redirect to run proccess
            }
        }
    }
} else if($back != "" && $ID > 0) {
    if($tabControl_active_tab == 'edit1') {
        LocalRedirect("/bitrix/admin/itgaziev.ozonyml_price_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&tabControl_active_tab=edit0");
    } else if($tabControl_active_tab == 'edit2') {
        LocalRedirect("/bitrix/admin/itgaziev.ozonyml_price_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&tabControl_active_tab=edit1");
    }
}

if($ID > 0) {
    $APPLICATION->SetTitle(Loc::getMessage('ITGAZIEV_OZONYML_PRICE_TITLE_HEAD', ['#ID#' => $ID]));
} else {
    $APPLICATION->SetTitle(Loc::getMessage('ITGAZIEV_OZONYML_PRICE_CREATE_TITLE_HEAD'));
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

$aMenu = array(
    array(
        'TEXT' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_BACK'),
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_BACK_TITLE'),
        'LINK' => 'itgaziev.ozonyml_price_list.php?lang='.LANG,
        'ICON' => 'btn_list'
    )
);

if($ID > 0) {
    $aMenu[] = array('SEPARATOR' => 'Y');

    $aMenu[] = array(
        'TEXT' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_ADD'),
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_ADD_TITLE'),
        'LINK' => 'itgaziev.ozonyml_price_edit.php?lang='.LANG,
        'ICON' => 'btn_new'
    );

    $aMenu[] = array(
        'TEXT' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_DELETE'),
        'TITLE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_DELETE_TITLE'),
        'LINK' => "javascript:if(confirm('" . Loc::getMessage("ITGAZIEV_OZONYML_PRICE_DELETE_CONF") . "')) window.location='itgaziev.ozonyml_price_list.php?ID=" . $ID . "&action=delete&lang=" . LANG . "&" . bitrix_sessid_get() . "';",
        'ICON' => 'btn_new'
    );

    $aMenu[] = array('SEPARATOR' => 'Y');
}

$context = new CAdminContextMenu($aMenu);

$context->Show();

if($ID > 0) {
    if($_REQUEST['mess'] == 'ok') {
        CAdminMessage::ShowMessage(array(
            'MESSAGE' => Loc::getMessage('ITGAZIEV_OZONYML_PRICE_SAVED'),
            'TYPE' => 'OK'
        ));
    }
}

//TODO : Get Iblock List

?>
<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?lang=<?=LANG?>" enctype="multipart/form-data" name="post_form">
<?php
echo bitrix_sessid_post();
$tabControl->Begin();
$tabControl->BeginNextTab();
// TODO : edit0 html
?>
<tr>
    <td><?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_ACTIVE") ?></td>
    <td>
        <input type="checkbox" name="ACTIVE" value="Y" <?=$condition['ACTIVE'] ? ($condition['ACTIVE'] == "Y" ? "checked" : "") : "checked"?>>
    </td>
</tr>
<? if($ID > 0): ?>
    <tr>
        <td width="40%">ID:</td>
        <td width="60%">
            <span><?= $ID ?></span>
            <input type="hidden" name="ID" value="<?= $ID ?>"/>
        </td>
    </tr>
<? endif; ?>
<tr>
    <td width="40%"><span class="required">*</span><?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_NAME") ?></td>
    <td width="60%"><input type="text" name="NAME" value="<?= $condition['NAME'] ?>" size="44" maxlength="255" /></td>
</tr>
<tr>
    <td width="40%">
        <span class="required">*</span><?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_IBLOCK") ?>
    </td>
    <td>
        <?= SelectBoxFromArray('IBLOCK', OzonYML\Main::getIBlockList(), $condition['IBLOCK'], '', 'style="min-width: 350px; margin-right: 5px;"', false, ''); ?>
    </td>
</tr>
<tr>
    <td width="40%">
        <?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_AGENT_TIME") ?>
    </td>
    <td>
        <?= SelectBoxFromArray('AGENT_TIME', OzonYML\Main::getAgentTime(), $condition['AGENT_TIME'], '', 'style="min-width: 350px; margin-right: 5px;"', false, ''); ?>
    </td>
</tr>
<?
$tabControl->BeginNextTab();
//TODO : edit1 html
$stores = OzonYML\Main::getStores();
?>
<tr>
    <td width="40%">
        Артикул озон
    </td>
    <td>
        <select name="PARAMETERS[SKU]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $condition['PARAMETERS']['SKU'] ?>"></select>
    </td>
</tr>
<tr>
    <td width="40%">
        Цена без скидки
    </td>
    <td>
        <select name="PARAMETERS[PRICE_BASE]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $condition['PARAMETERS']['PRICE_BASE'] ?>"></select>
    </td>
</tr>
<tr>
    <td width="40%">
        Цена со скидкой
    </td>
    <td>
        <select name="PARAMETERS[PRICE_DISCOUNT]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $condition['PARAMETERS']['PRICE_DISCOUNT'] ?>"></select>
    </td>
</tr>
<tr>
    <td width="40%">
        Цена премиум
    </td>
    <td>
        <select name="PARAMETERS[PRICE_PREMIUM]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $condition['PARAMETERS']['PRICE_PREMIUM'] ?>"></select>
    </td>
</tr>
<tr>
    <td colspan="2" width="100%" style="text-align: center">Склады</td>
</tr>
<tr>
    <td colspan="2">
        <div class="add-row-stores-js">
            <table style="background-color: #ccc; padding: 1rem; margin-bottom: 1rem;" class="table-stores">
                <td width="40%" style="text-align: right;" class="title-td">
                    Склад #1
                </td>
                <td>
                    <select name="PARAMETERS[OUTLETS][0][ID]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $condition['PARAMETERS']['OUTLETS'][0]['ID'] ?>"></select>
                    <input name="PARAMETERS[OUTLETS][0][NAME]" type="text" value="<?= $condition['PARAMETERS']['OUTLETS'][0]['NAME'] ?>" size="44" maxlength="255" placeholder="Название склада как в озон"/>
                </td>
            </table>
            <? foreach($condition['PARAMETERS']['OUTLETS'] as $i => $outlets): ?>
                <? if($i === 0) continue; ?>
                <table style="background-color: #ccc; padding: 1rem; margin-bottom: 1rem; position: relative;" class="table-stores" data-index="<?= $i ?>">
                    <td width="40%" style="text-align: right;" class="title-td">
                        <i class="remove-row-store" data-index="<?= $i ?>">x</i>
                        Склад #<?= $i ?>
                    </td>
                    <td>
                        <select name="PARAMETERS[OUTLETS][<?=$i?>][ID]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js" data-selected="<?= $outlets['ID'] ?>"></select>
                        <input name="PARAMETERS[OUTLETS][<?=$i?>][NAME]" type="text" value="<?= $outlets['NAME'] ?>" size="44" maxlength="255" placeholder="Название склада как в озон"/>
                    </td>
                </table>
            <? endforeach; ?>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2" style="text-align: center">
        <button class="adm-btn-save js-add-stores" type="button" style="padding: 0.5rem 1rem;">Добавить склад</td>
    </td>
</tr>
<?
//echo '<pre>'; print_r($stores); echo '</pre>';
$tabControl->BeginNextTab();
//TODO : edit2 html
?>
<div class="box">
    <table>

    </table>
</div>
<?
$tabControl->Buttons();

//TODO : Buttons
switch ($tabControl_active_tab) {
    case 'edit1':
        echo '<input type="submit" name="back" value="<< Назад" title="">';
        echo '<input type="submit" name="next" value="Далее >>" title="Перейти к следующему шагу" class="adm-btn-save">';
        break;
    case 'edit2':
            echo '<input type="submit" name="back" value="<< Назад" title="">';
            echo '<input type="submit" name="next" value="Запустить  импорт" title="Перейти к следующему шагу" class="adm-btn-save">';
            break;
    default:
        echo '<input type="submit" name="cancel" value="Отменить" onclick="top.window.location=\'itgaziev.ozonyml_price_list.php?lang='. LANG . '\'" title="' . Loc::getMessage('ITGAZIEV_OZONYML_PRICE_CANCEL') . '">';
        echo '<input type="submit" name="next" value="Далее >>" title="Перейти к следующему шагу" class="adm-btn-save">';

}
$tabControl->End();
?></form>
<?php
//TODO : Scripts

ob_start();
?>
<script>
var data = <?= json_encode(OzonYML\Main::getArraysSelect($condition['IBLOCK'])) ?>;

window.onload = () => {
    let sku = <?= json_encode(OzonYML\Main::getFieldIblock()) ?>;
    $('.ozonyml-price-select-js').select2({data : data, width : 'style', placeholder : 'Выберите значение'});
}
$(function(){
    $('.adm-detail-tab').attr('onclick', '');
    $('.adm-detail-tab:not(.adm-detail-tab-active)').addClass('adm-detail-tab-disable');

    $(document).on('click', '.js-add-stores', function(e) {
        e.preventDefault();
        let list = $('.table-stores');
        let index = list.length;
        let template = `
            <table style="background-color: #ccc; padding: 1rem; margin-bottom: 1rem; position: relative;" class="table-stores" data-index="${index}">
                <td width="40%" style="text-align: right;" class="title-td">
                    <i class="remove-row-store" data-index="${index}">x</i>
                    Склад #${index+1}
                </td>
                <td>
                    <select name="PARAMETERS[OUTLETS][${list.length}][ID]" style="min-width: 350px; margin-right: 5px;" class="ozonyml-price-select-js"></select>
                    <input name="PARAMETERS[OUTLETS][${list.length}][NAME]" type="text" value="" size="44" maxlength="255" placeholder="Название склада как в озон"/>
                </td>
            </table>
        `;

        $('.add-row-stores-js').append(template);

        $('.table-stores[data-index="' + index + '"] .ozonyml-price-select-js').select2({data : data, width : 'style', placeholder : 'Выберите значение'});
    })
    $(document).on('click', 'i.remove-row-store', function(){
        let index = $(this).attr('data-index');
        $('.table-stores[data-index="' + index + '"]').remove();
        let reindex = 0;
        $('.table-stores').each(function() {
            if(reindex !== 0) {
            $(this).attr('data-index', reindex);
            $(this).find('select.ozonyml-price-select-js').attr('name', 'PARAMETERS[OUTLETS]['+reindex+'][ID]');
            $(this).find('input').attr('name', 'PARAMETERS[OUTLETS]['+reindex+'][NAME]');
            $(this).find('.title-td').html(`
                    <i class="remove-row-store" data-index="${reindex}">x</i>
                    Склад #${reindex+1}
            `)
            }
            reindex++;
        });
    })
});
</script>
<?
$jsString = ob_get_clean();
Asset::getInstance()->addString($jsString);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';