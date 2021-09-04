const songsList = document.getElementById('songsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getSongsList() {
    let requestRoute = `/admin/ajax-songs/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`;
    requestRoute = requestRoute.replaceAll(' ', '+');
    requestRoute = encodeURI(requestRoute);

    let newRoute = `/admin/partitions?sort=${sortInput.value}&query=${searchInput.value}`;
    newRoute = newRoute.replaceAll(' ', '+');
    newRoute = encodeURI(newRoute);

    fetch(requestRoute)
        .then((response) => response.text())
        .then((list) => {
            songsList.innerHTML = list;
            const state = { sort: sortInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                newRoute,
            );
        });
}

searchInput.addEventListener('input', () => {
    getSongsList();
});
sortInput.addEventListener('change', () => {
    getSongsList();
});
