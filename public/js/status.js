document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.card table tbody tr');

    rows.forEach((row, index) => {
      row.style.animationDelay = `${index * 0.2}s`;
    });
});