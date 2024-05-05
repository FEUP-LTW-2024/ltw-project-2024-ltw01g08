// Function to toggle the dropdown menu
function toggleProfileDropdown() {
    const dropdown = document.getElementById('dropdown-menu');
    dropdown.classList.toggle('show');
}

// Event listener for clicking the profile icon
document.getElementById('profile-icon').addEventListener('click', function (event) {
    event.stopPropagation(); // Prevents triggering the window click event
    toggleProfileDropdown();
});

// Event listener for clicking outside the dropdown to close it
window.addEventListener('click', function () {
    const dropdowns = document.getElementsByClassName('dropdown-content');
    for (let i = 0; i < dropdowns.length; i++) {
        const openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
        }
    }
});
