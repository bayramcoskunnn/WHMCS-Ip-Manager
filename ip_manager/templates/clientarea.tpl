<div class="ip-manager-container">
    <h2 class="ip-manager-title">IP Yönetimi</h2>

    <div class="ip-table-container">
        <div class="ip-table-actions">
            <input type="text" id="ip-search" placeholder="IP veya rDNS ara..." class="ip-search-input">
            <select id="ip-sort" class="ip-sort-select">
                <option value="ip">IP'ye göre sırala</option>
                <option value="date">Tarihe göre sırala</option>
                <option value="client">Müşteri Adına göre sırala</option>
            </select>
        </div>
        <div class="ip-table-wrapper">
            <table class="ip-table">
                <thead>
                    <tr>
                        <th>IP Adresi</th>
                        <th>rDNS</th>
                        <th>Atanma Tarihi</th>
                        <th>Müşteri Adı</th>
                    </tr>
                </thead>
                <tbody id="ip-table-body">
                    {if $allIps->count() > 0}
                        {foreach $allIps as $ip}
                            <tr>
                                <td><span class="ip-badge">{$ip->ip_address}</span></td>
                                <td>{$ip->rdns|default:"N/A"}</td>
                                <td>{$ip->assigned_date|date_format:"%d.%m.%Y %H:%M"|default:"N/A"}</td>
                                <td>{$ip->client_firstname} {$ip->client_lastname}</td>
                            </tr>
                        {/foreach}
                    {else}
                        <tr>
                            <td colspan="4" class="no-ip-message">Henüz atanmış IP adresi bulunmamaktadır.</td>
                        </tr>
                    {/if}
                </tbody>
            </table>
        </div>
    </div>

    <div class="debug-info">
        <h3>Debug Bilgisi</h3>
        <p>Mevcut Kullanıcı Adı: {$clientfirstname}</p>
        <p>Toplam IP Sayısı: <span id="ip-count">{$allIps->count()}</span></p>
    </div>
</div>

<style>
    :root {
        --primary-color: #4a90e2;
        --secondary-color: #f5f7fa;
        --text-color: #333;
        --border-color: #e0e0e0;
    }

    .ip-manager-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .ip-manager-title {
        color: var(--primary-color);
        font-size: 2.5rem;
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 300;
        letter-spacing: -0.5px;
    }

    .ip-table-container {
        background-color: var(--secondary-color);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .ip-table-actions {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        background-color: #ffffff;
        border-bottom: 1px solid var(--border-color);
    }

    .ip-search-input, .ip-sort-select {
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .ip-search-input:focus, .ip-sort-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .ip-search-input {
        width: 60%;
    }

    .ip-sort-select {
        width: 35%;
    }

    .ip-table-wrapper {
        overflow-x: auto;
    }

    .ip-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .ip-table th, .ip-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .ip-table th {
        background-color: var(--primary-color);
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .ip-table tr:last-child td {
        border-bottom: none;
    }

    .ip-table tr {
        transition: background-color 0.3s ease;
    }

    .ip-table tr:hover {
        background-color: rgba(74, 144, 226, 0.1);
    }

    .ip-badge {
        background-color: var(--primary-color);
        color: #fff;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .ip-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .no-ip-message {
        text-align: center;
        color: #6c757d;
        font-style: italic;
        font-size: 1rem;
        padding: 2rem;
    }

    .debug-info {
        margin-top: 2rem;
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .debug-info h3 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .ip-table-actions {
            flex-direction: column;
        }
        .ip-search-input, .ip-sort-select {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .ip-table th, .ip-table td {
            padding: 0.75rem;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .ip-table tr {
        animation: fadeIn 0.5s ease-out;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ip-search');
    const sortSelect = document.getElementById('ip-sort');
    const tableBody = document.getElementById('ip-table-body');
    const rows = Array.from(tableBody.querySelectorAll('tr'));
    const ipCountElement = document.getElementById('ip-count');

    searchInput.addEventListener('input', filterTable);
    sortSelect.addEventListener('change', sortTable);

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        ipCountElement.textContent = visibleCount;
        animateRows();
    }

    function sortTable() {
        const sortBy = sortSelect.value;
        const sortedRows = rows.sort((a, b) => {
            let aValue, bValue;
            switch(sortBy) {
                case 'ip':
                    aValue = a.cells[0].textContent;
                    bValue = b.cells[0].textContent;
                    return compareIP(aValue, bValue);
                case 'date':
                    aValue = new Date(a.cells[2].textContent);
                    bValue = new Date(b.cells[2].textContent);
                    return aValue - bValue;
                case 'client':
                    aValue = a.cells[3].textContent;
                    bValue = b.cells[3].textContent;
                    return aValue.localeCompare(bValue);
            }
        });
        tableBody.append(...sortedRows);
        animateRows();
    }

    function compareIP(a, b) {
        const aParts = a.split('.');
        const bParts = b.split('.');
        for (let i = 0; i < 4; i++) {
            const aPart = parseInt(aParts[i]);
            const bPart = parseInt(bParts[i]);
            if (aPart !== bPart) {
                return aPart - bPart;
            }
        }
        return 0;
    }

    function animateRows() {
        rows.forEach((row, index) => {
            row.style.animation = 'none';
            row.offsetHeight; // Trigger reflow
            row.style.animation = `fadeIn 0.5s ease-out ${index * 0.05}s`;
        });
    }

    // Initial animation
    animateRows();
});
</script>