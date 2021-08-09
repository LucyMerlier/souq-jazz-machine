const newsList = document.getElementById('newsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getNews() {
    fetch(`/admin/ajax-news/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            newsList.innerHTML = list;
        });
}

searchInput.addEventListener('input', () => {
    getNews();
});
sortInput.addEventListener('change', () => {
    getNews();
});
