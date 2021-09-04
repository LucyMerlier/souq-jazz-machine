const albumsList = document.getElementById('albumsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getAlbumsList() {
    let requestRoute = `/admin/ajax-albums/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`;
    requestRoute = requestRoute.replaceAll(' ', '+');
    requestRoute = encodeURI(requestRoute);

    let newRoute = `/admin/albums-photos?sort=${sortInput.value}&query=${searchInput.value}`;
    newRoute = newRoute.replaceAll(' ', '+');
    newRoute = encodeURI(newRoute);

    fetch(requestRoute)
        .then((response) => response.text())
        .then((list) => {
            albumsList.innerHTML = list;
            const state = { sort: sortInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                newRoute,
            );
        });
}

searchInput.addEventListener('input', () => {
    getAlbumsList();
});
sortInput.addEventListener('change', () => {
    getAlbumsList();
});
