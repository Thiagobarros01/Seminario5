document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.querySelector('.dropdown');

    dropdown.addEventListener('mouseover', function () {
        const menu = dropdown.querySelector('.dropdown-menu');
        menu.style.display = 'block';
    });

    dropdown.addEventListener('mouseout', function () {
        const menu = dropdown.querySelector('.dropdown-menu');
        menu.style.display = 'none';
    });
});
