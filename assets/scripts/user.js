const membersList = document.getElementById('membersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('instrument');

searchInput.addEventListener('input', (event) => {
    fetch(`/admin/ajax-users/${filterInput.value === '' ? '0' : filterInput.value}/${event.target.value}`)
        .then((response) => response.text())
        .then((list) => {
            membersList.innerHTML = list;
        });
});
