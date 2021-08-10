const albumsList = document.getElementById('albumsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getAlbums() {
    fetch(`/admin/ajax-albums/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            albumsList.innerHTML = list;
            const state = { sort: sortInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/albums-photos?sort=${sortInput.value}&query=${searchInput.value}`,
            );
        });
}

searchInput.addEventListener('input', () => {
    getAlbums();
});
sortInput.addEventListener('change', () => {
    getAlbums();
});
