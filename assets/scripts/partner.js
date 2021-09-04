const partnersList = document.getElementById('partnersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('category');

function getPartnersList() {
    let requestRoute = `/admin/ajax-partners/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`;
    requestRoute = requestRoute.replaceAll(' ', '+');
    requestRoute = encodeURI(requestRoute);

    let newRoute = `/admin/contacts?category=${filterInput.value}&query=${searchInput.value}`;
    newRoute = newRoute.replaceAll(' ', '+');
    newRoute = encodeURI(newRoute);

    fetch(requestRoute)
        .then((response) => response.text())
        .then((list) => {
            partnersList.innerHTML = list;
            const state = { filter: filterInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                newRoute,
            );
        });
}

searchInput.addEventListener('input', () => {
    getPartnersList();
});
filterInput.addEventListener('change', () => {
    getPartnersList();
});
