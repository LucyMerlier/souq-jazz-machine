const concertsList = document.getElementById('concertsList');
const sortInput = document.getElementById('sort');

function getConcertsList() {
    fetch(`/admin/ajax-concerts/${sortInput.value === '' ? '0' : sortInput.value}`)
        .then((response) => response.text())
        .then((list) => {
            concertsList.innerHTML = list;
            const state = { sort: sortInput.value };
            window.history.replaceState(
                { state },
                '',
                `/admin/concerts?sort=${sortInput.value}`,
            );
        });
}

sortInput.addEventListener('change', () => {
    getConcertsList();
});
