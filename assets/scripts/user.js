const membersList = document.getElementById('membersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('instrument');

function getUsersList() {
    let requestRoute = `/admin/ajax-users/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`;
    requestRoute = requestRoute.replaceAll(' ', '+');
    requestRoute = encodeURI(requestRoute);

    let newRoute = `/admin/membres?instrument=${filterInput.value}&query=${searchInput.value}`;
    newRoute = newRoute.replaceAll(' ', '+');
    newRoute = encodeURI(newRoute);

    fetch(requestRoute)
        .then((response) => response.text())
        .then((list) => {
            membersList.innerHTML = list;
            const state = { filter: filterInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                newRoute,
            );
        });
}

searchInput.addEventListener('input', () => {
    getUsersList();
});
filterInput.addEventListener('change', () => {
    getUsersList();
});
