document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('site-search');
    const tableRows = document.querySelectorAll('.table-container tbody tr');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            let matchFound = false;
            const cells = row.querySelectorAll('td');

            cells.forEach(cell => {
                const cellText = cell.textContent.toLowerCase();
                if (cellText.includes(searchTerm)) {
                    matchFound = true;
                }
            });

            if (matchFound) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});