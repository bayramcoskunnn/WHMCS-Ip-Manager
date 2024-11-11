<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\View\Menu\Item as MenuItem;

add_hook('ClientAreaPrimaryNavbar', 1, function (MenuItem $primaryNavbar) {
    if (!is_null($primaryNavbar)) {
        $primaryNavbar->addChild('IP Yönetimi', [
            'uri' => 'index.php?m=ip_manager',
            'order' => 70
        ]);
    }
});