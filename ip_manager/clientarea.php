<?php

use WHMCS\Database\Capsule;

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function ip_manager_clientarea($vars) {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action != 'ip_manager') {
        return [];
    }

    $clientId = $_SESSION['uid'];
    
    try {
        $ips = Capsule::table('mod_ip_manager')
            ->where('client_id', $clientId)
            ->get();
    } catch (\Exception $e) {
        $ips = [];
    }

    return [
        'pagetitle' => 'IP Yönetimi',
        'breadcrumb' => [
            'index.php?action=ip_manager' => 'IP Yönetimi',
        ],
        'templatefile' => 'templates/clientarea',
        'requirelogin' => true,
        'vars' => [
            'ips' => $ips,
        ],
    ];
}