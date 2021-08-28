const newsList = document.getElementById('newsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getNewsList() {
    fetch(`/admin/ajax-news/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            newsList.innerHTML = list;
            const state = { sort: sortInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/gestion-des-actus?sort=${sortInput.value}&query=${searchInput.value}`,
            );
        });
}

searchInput.addEventListener('input', () => {
    getNewsList();
});
sortInput.addEventListener('change', () => {
    getNewsList();
});
