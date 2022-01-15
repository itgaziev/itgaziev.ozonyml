<?php
use Bitrix\Main;

Main\Loader::registerAutoLoadClasses('itgaziev.ozonyml', [
    'ITGaziev\OzonYML\Table\ITGazievOzonYML' => '/lib/table/itgaziev_ozonyml.php',
]);