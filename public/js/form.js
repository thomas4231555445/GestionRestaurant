document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btncave');
    const formContainer = document.getElementById('form-container');
    const form = formContainer.querySelector('form');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const row = button.getAttribute('data-row');
            const col = button.getAttribute('data-col');
            formContainer.style.display = 'block';

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                formData.append('ligne', String.fromCharCode(65 + parseInt(row))); // Convert row to letter (A, B, C, ...)
                formData.append('colonne', col + 1); // Convert col to 1-based index

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })

                    .then(data => {
                        button.setAttribute('name', `cell-${data.ligne}-${data.colonne}`);
                        formContainer.style.display = 'none';
                        console.log("bonjour")
                    })

            });
        });
    });
});