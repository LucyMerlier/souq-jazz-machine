const newsList = document.getElementById('newsList');
const searchInput = document.getElementById('query');
const sortInput = document.getElementById('sort');

function getNewsList() {
    let requestRoute = `/admin/ajax-news/${sortInput.value === '' ? '0' : sortInput.value}/${searchInput.value}`;
    requestRoute = requestRoute.replaceAll(' ', '+');
    requestRoute = encodeURI(requestRoute);

    let newRoute = `/admin/gestion-des-actus?sort=${sortInput.value}&query=${searchInput.value}`;
    newRoute = newRoute.replaceAll(' ', '+');
    newRoute = encodeURI(newRoute);

    fetch(requestRoute)
        .then((response) => response.text())
        .then((list) => {
            newsList.innerHTML = list;
            const state = { sort: sortInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                newRoute,
            );
        });
}

searchInput.addEventListener('input', () => {
    getNewsList();
});
sortInput.addEventListener('change', () => {
    getNewsList();
});
