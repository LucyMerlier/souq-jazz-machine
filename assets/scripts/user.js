const membersList = document.getElementById('membersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('instrument');

function getUsersList() {
    fetch(`/admin/ajax-users/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            membersList.innerHTML = list;
            const state = { filter: filterInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/membres?instrument=${filterInput.value}&query=${searchInput.value}`,
            );
        });
}

searchInput.addEventListener('input', () => {
    getUsersList();
});
filterInput.addEventListener('change', () => {
    getUsersList();
});
