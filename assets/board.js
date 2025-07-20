document.addEventListener('DOMContentLoaded', () => {
    const columns = document.querySelectorAll('.column');
    let draggedTask = null;
    console.log(columns)
    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
        });

        column.addEventListener('drop', e => {
            if (draggedTask) {
                column.appendChild(draggedTask);
                draggedTask = null;
            }
        });
    });

    document.querySelectorAll('.task').forEach(task => {
        task.addEventListener('dragstart', e => {
            draggedTask = e.target;
        });
    });
});
