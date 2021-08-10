const membersList = document.getElementById('membersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('instrument');

function getUsers() {
    fetch(`/admin/ajax-users/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            membersList.innerHTML = list;
            const state = { filter: filterInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/membres-du-groupe?instrument=${filterInput.value}&query=${searchInput.value}`,
            );
        });
}

searchInput.addEventListener('input', () => {
    getUsers();
});
filterInput.addEventListener('change', () => {
    getUsers();
});
