const approveButtons = document.querySelectorAll('btn#btn-approve');
const approveModal = document.querySelector('#modal-approve');
const approveModalBs = new bootstrap.Modal(approveModal);

approveButtons.forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr');
        const cells = Array.from(row.cells);

        const id = row.getAttribute("data-id");
        const data = [];

        // Get all data
        cells.pop();
        cells.forEach(cell => {
            const content = cell.textContent.trim();
            if (content) {
                data.push(content);
            }
        })

        approveModal.querySelector('#approve-summary').textContent = data.join('\n');
        approveModal.querySelector('form').reset();

        approveModalBs.show();
    });
});
