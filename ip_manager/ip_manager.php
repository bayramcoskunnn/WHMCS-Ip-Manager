<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

function ip_manager_config() {
    return [
        'name' => 'IP Yönetici',
        'description' => 'Müşterilere IP adresi atamak ve yönetmek için modül',
        'version' => '1.0',
        'author' => 'Bayram Coşkun',
        'fields' => [],
        'clientareaname' => 'IP Yönetimi'
    ];
}

function ip_manager_activate() {
    try {
        if (!Capsule::schema()->hasTable('mod_ip_manager')) {
            Capsule::schema()->create('mod_ip_manager', function ($table) {
                $table->increments('id');
                $table->integer('client_id');
                $table->string('ip_address', 45);
                $table->string('rdns', 255)->nullable();
                $table->timestamp('assigned_date');
            });
        } else {
            if (!Capsule::schema()->hasColumn('mod_ip_manager', 'rdns')) {
                Capsule::schema()->table('mod_ip_manager', function ($table) {
                    $table->string('rdns', 255)->nullable();
                });
            }
        }
        return [
            'status' => 'success',
            'description' => 'IP Yönetici modülü başarıyla etkinleştirildi.',
        ];
    } catch (\Exception $e) {
        return [
            'status' => "error",
            'description' => 'Unable to create/update mod_ip_manager: ' . $e->getMessage(),
        ];
    }
}

function ip_manager_deactivate() {
    try {
        Capsule::schema()->dropIfExists('mod_ip_manager');
        return [
            'status' => 'success',
            'description' => 'IP Yönetici modülü başarıyla devre dışı bırakıldı.',
        ];
    } catch (\Exception $e) {
        return [
            "status" => "error",
            "description" => "Unable to drop mod_ip_manager: {$e->getMessage()}",
        ];
    }
}

function ip_manager_output($vars) {
    if (isset($_POST['assign_ip'])) {
        $client_id = (int)$_POST['client_id'];
        $ip_address = $_POST['ip_address'];
        $date = date('Y-m-d H:i:s');
        $rdns = get_rdns($ip_address);

        try {
            $result = Capsule::table('mod_ip_manager')->insert([
                'client_id' => $client_id,
                'ip_address' => $ip_address,
                'rdns' => $rdns,
                'assigned_date' => $date
            ]);
            if ($result) {
                echo "IP başarıyla atandı. Client ID: $client_id, IP: $ip_address";
            } else {
                echo "IP atama başarısız oldu.";
            }
        } catch (\Exception $e) {
            echo "IP atama sırasında bir hata oluştu: " . $e->getMessage();
        }
    }

    if (isset($_POST['delete_ip'])) {
        $ip_id = (int)$_POST['ip_id'];

        try {
            Capsule::table('mod_ip_manager')->where('id', $ip_id)->delete();
            echo "IP başarıyla silindi.";
        } catch (\Exception $e) {
            echo "IP silme sırasında bir hata oluştu: " . $e->getMessage();
        }
    }

    $ips = Capsule::table('mod_ip_manager')->get();

    require_once(__DIR__ . '/templates/admin.php');
}

function ip_manager_clientarea($vars) {
    $clientId = $vars['clientid'];
    
    $allIps = Capsule::table('mod_ip_manager')
        ->join('tblclients', 'mod_ip_manager.client_id', '=', 'tblclients.id')
        ->select('mod_ip_manager.*', 'tblclients.firstname', 'tblclients.lastname')
        ->get();
    
    $ips = Capsule::table('mod_ip_manager')
        ->join('tblclients', 'mod_ip_manager.client_id', '=', 'tblclients.id')
        ->select('mod_ip_manager.*', 'tblclients.firstname', 'tblclients.lastname')
        ->where('mod_ip_manager.client_id', $clientId)
        ->get();

    return [
        'pagetitle' => 'IP Yönetimi',
        'breadcrumb' => [
            'index.php?m=ip_manager' => 'IP Yönetimi',
        ],
        'templatefile' => 'clientarea',
        'requirelogin' => true,
        'vars' => [
            'ips' => $ips,
            'clientId' => $clientId,
            'allIps' => $allIps,
        ],
    ];
}


function get_rdns($ip) {
    try {
        $rdns = gethostbyaddr($ip);
        return ($rdns !== $ip) ? $rdns : 'Kayıt bulunamadı';
    } catch (\Exception $e) {
        return 'Sorgu hatası';
    }
}
