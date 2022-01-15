<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;

Loc::loadMessages(__FILE__);

class ITGaziev_OzonYML extends CModule {
    var $exclusionAdminFiles;

    function __construct() {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');

        $this->exclusionAdminFiles = array(
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php'
        );

        $this->MODULE_ID = ITMODULE_NAME;
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('ITGAZIEV_OZONYML_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('ITGAZIEV_OZONYML_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('ITGAZIEV_OZONYML_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('ITGAZIEV_OZONYML_PARTNER_URI');

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    function InstallDB() {

    }

    function UnInstallDB() {

    }

    function InstallEvents() {

    }

    function UnInstallEvents() {

    }

    function InstallFiles() {

    }

    function UnInstallFiles() {

    }

    function DoInstall() {

    }

    function DoUnInstall() {

    }

    function isVersionD7() {

    }

    function GetPath($notDocumentRoot = false) {

    }

    function GetModuleRightsList() {
        return array(
            'reference_id' => array('D', 'K', 'S', 'W'),
            'reference' => array(
                '[D]' . Loc::getMessage('ITGAZIEV_OZONYML_DENIED'),
                '[K]' . Loc::getMessage('ITGAZIEV_OZONYML_READ_COMPONENT'),
                '[S]' . Loc::getMessage("ITGAZIEV_OZONYML_WRITE_SETTINGS"),
                '[W]' . Loc::getMessage("ITGAZIEV_OZONYML_FULL")
            )
        );
    }
}