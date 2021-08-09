const songsList = document.getElementById('songsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

searchInput.addEventListener('input', (event) => {
    fetch(`/admin/ajax-songs/${sortInput.value === '' ? '0' : sortInput.value}/${event.target.value}`)
        .then((response) => response.text())
        .then((list) => {
            songsList.innerHTML = list;
        });
});
