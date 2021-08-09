const songsList = document.getElementById('songsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getSongs() {
    fetch(`/admin/ajax-songs/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            songsList.innerHTML = list;
        });
}

searchInput.addEventListener('input', () => {
    getSongs();
});
sortInput.addEventListener('change', () => {
    getSongs();
});
