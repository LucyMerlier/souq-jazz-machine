const partnersList = document.getElementById('partnersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('category');

function getPartners() {
    fetch(`/admin/ajax-partners/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            partnersList.innerHTML = list;
            const state = { filter: filterInput.value, search: searchInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/contacts?category=${filterInput.value}&query=${searchInput.value}`,
            );
        });
}

searchInput.addEventListener('input', () => {
    getPartners();
});
filterInput.addEventListener('change', () => {
    getPartners();
});
