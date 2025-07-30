function initDragDrop() {
    const columns = document.querySelectorAll('.column');
    let draggedTask = null;
    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
        });
        console.log(1)
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
}

document.addEventListener('DOMContentLoaded', initDragDrop);
document.addEventListener('turbo:load', initDragDrop);