const partnersList = document.getElementById('partnersList');
const searchInput = document.getElementById('query');
const filterInput = document.getElementById('category');

function getPartners() {
    fetch(`/admin/ajax-partners/${filterInput.value === '' ? '0' : filterInput.value}/${searchInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            partnersList.innerHTML = list;
        });
}

searchInput.addEventListener('input', () => {
    getPartners();
});
filterInput.addEventListener('change', () => {
    getPartners();
});
