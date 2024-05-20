function toggleProfileDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.classList.toggle('show');
}

document.getElementById('profile-icon').addEventListener('click', function (event) {
    event.stopPropagation(); 
    toggleProfileDropdown();
});

window.addEventListener('click', function () {
    const dropdowns = document.getElementsByClassName('dropdown-content');
    for (let i = 0; i < dropdowns.length; i++) {
        const openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
    }
});
