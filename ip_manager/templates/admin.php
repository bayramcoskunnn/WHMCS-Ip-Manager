<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;
?>

<style>
    .ip-manager-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
    }
    .ip-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .ip-form {
        display: grid;
        gap: 1rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
    }
    .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out;
    }
    .form-control:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
    }
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.15s ease-in-out;
    }
    .btn-ip-assign {
        background-color: #4299e1;
        color: white;
        border: none;
    }
    .btn-ip-assign:hover {
        background-color: #3182ce;
    }
    .btn-ip-delete {
        background-color: #e53e3e;
        color: white;
        border: none;
    }
    .btn-ip-delete:hover {
        background-color: #c53030;
    }
    .ip-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }
    .ip-table th, .ip-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .ip-table th {
        background-color: #f7fafc;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }
    .ip-table tr:last-child td {
        border-bottom: none;
    }
    .ip-table tbody tr:hover {
        background-color: #f7fafc;
    }
    @media (max-width: 768px) {
        .ip-form {
            grid-template-columns: 1fr;
        }
    }
    .footer-image {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    .footer-image img {
        max-width: 100%;
        height: auto;
    }
</style>

<div class="ip-manager-container">
    <h2 class="text-3xl font-bold mb-6">IP Yönetici</h2>

    <div class="ip-card">
        <h3 class="text-xl font-semibold mb-4">Yeni IP Ata</h3>
        <form method="post" action="" class="ip-form">
            <div class="form-group">
                <label for="client_id">Müşteri Seçin</label>
                <select name="client_id" id="client_id" class="form-control">
                    <?php
                    $clients = localAPI('GetClients', []);
                    foreach ($clients['clients']['client'] as $client) {
                        echo "<option value='{$client['id']}'>{$client['firstname']} {$client['lastname']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ip_address">IP Adresi</label>
                <input type="text" name="ip_address" id="ip_address" class="form-control" placeholder="Örn: 192.168.1.1" required>
            </div>
            <button type="submit" name="assign_ip" class="btn btn-ip-assign">IP Ata</button>
        </form>
    </div>

    <div class="ip-card">
        <h3 class="text-xl font-semibold mb-4">Atanmış IP'ler</h3>
        <div class="overflow-x-auto">
            <table class="ip-table">
                <thead>
                    <tr>
                        <th>Müşteri</th>
                        <th>IP Adresi</th>
                        <th>rDNS</th>
                        <th>Atanma Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ips as $ip) {
                        $client = localAPI('GetClientsDetails', ['clientid' => $ip->client_id]);
                        echo "<tr>";
                        echo "<td>{$client['firstname']} {$client['lastname']}</td>";
                        echo "<td>{$ip->ip_address}</td>";
                        echo "<td>{$ip->rdns}</td>";
                        echo "<td>{$ip->assigned_date}</td>";
                        echo "<td>
                                <form method='post' action='' style='display:inline;'>
                                    <input type='hidden' name='ip_id' value='{$ip->id}'>
                                    <button type='submit' name='delete_ip' class='btn btn-ip-delete'>Sil</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<div class="footer-image">
    <img src="https://hostvu.com.tr/ata.png" alt="Atatürk" width="1320" height="100">
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const ipInput = document.getElementById('ip_address');
    ipInput.addEventListener('input', function(e) {
        let value = e.target.value;
        let parts = value.split('.');
        if (parts.length > 4) {
            parts = parts.slice(0, 4);
        }
        parts = parts.map(part => part.replace(/\D/g, '').slice(0, 3));
        e.target.value = parts.join('.');
    });
});
</script>