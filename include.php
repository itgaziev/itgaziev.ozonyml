<?php
use Bitrix\Main;

Main\Loader::registerAutoLoadClasses('itgaziev.ozonyml', [
    'ITGaziev\OzonYML\Table\ITGazievOzonYMLTable' => '/lib/table/itgaziev_ozonyml.php',
]);