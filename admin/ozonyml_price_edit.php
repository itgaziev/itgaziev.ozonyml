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
        <? //ShowJSHint('Выбирети инфоблок'); ?>
        <span class="required">*</span><?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_IBLOCK") ?>
    </td>
    <td>
        <?= SelectBoxFromArray('IBLOCK', OzonYML\Main::getIBlockList(), $condition['IBLOCK'], '', 'style="min-width: 350px; margin-right: 5px;"', false, ''); ?>
    </td>
</tr>
<tr>
    <td width="40%">
        <? //ShowJSHint('Выбирети инфоблок'); ?>
        <?= Loc::getMessage("ITGAZIEV_OZONYML_PRICE_FIELD_AGENT_TIME") ?>
    </td>
    <td>
        <?= SelectBoxFromArray('AGENT_TIME', OzonYML\Main::getAgentTime(), $condition['AGENT_TIME'], '', 'style="min-width: 350px; margin-right: 5px;"', false, ''); ?>
    </td>
</tr>
<?
$tabControl->BeginNextTab();
//TODO : edit1 html

$tabControl->BeginNextTab();
//TODO : edit2 html

$tabControl->Buttons();

//TODO : Buttons
switch ($tabControl_active_tab) {
    case 'edit1':
        echo '<input type="submit" name="back" value="<< Назад" title="">';
        echo '<input type="submit" name="next" value="Далее >>" title="Перейти к следующему шагу" class="adm-btn-save">';
        break;
    case 'edit2':
            echo '<input type="submit" name="back" value="<< Назад" title="">';
            echo '<input type="submit" name="next" value="Далее >>" title="Перейти к следующему шагу" class="adm-btn-save">';
            break;
    default:
        echo '<input type="submit" name="cancel" value="Отменить" onclick="top.window.location=\'itgaziev.ozonyml_price_list.php?lang='. LANG . '\'" title="' . Loc::getMessage('ITGAZIEV_OZONYML_PRICE_CANCEL') . '">';
        echo '<input type="submit" name="next" value="Далее >>" title="Перейти к следующему шагу" class="adm-btn-save">';

}
$tabControl->End();
?></form><?php
//TODO : Scripts
ob_start();
?>
<script>
$(function(){
    $('.adm-detail-tab').attr('onclick', '');
    $('.adm-detail-tab:not(.adm-detail-tab-active)').addClass('adm-detail-tab-disable');
});
</script>
<?
$jsString = ob_get_clean();
Asset::getInstance()->addString($jsString);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';