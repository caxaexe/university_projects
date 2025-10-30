document.getElementById('showAllBreedsArticles').addEventListener('click', function () {
    window.location.href = './allbreeds.html';
});

document.getElementById('showAllKittensArticles').addEventListener('click', function() {
    window.location.href = './kittens.html';
});

document.getElementById('showAllHealthArticles').addEventListener('click', function() {
    window.location.href = './health.html';
});

document.getElementById('showAllBehaviorArticles').addEventListener('click', function() {
    window.location.href = './behavior.html';
});

document.getElementById('showAllfeedingArticles').addEventListener('click', function() {
    window.location.href = './feeding.html';
});

document.getElementById('showAllCareArticles').addEventListener('click', function() {
    window.location.href = './care.html';
});


function redirectToPage(url) {
    window.open(url, '_blank');
}


function searchCat(event) {
    event.preventDefault();

    const input = document.getElementById('search-input').value.toLowerCase();
    const catList = document.getElementById('cat-list');
    const notFound = document.getElementById('not-found');
    const catItems = catList.getElementsByClassName('cat-item');
    let found = false;

    Array.from(catItems).forEach(catItem => {
        const catName = catItem.getElementsByTagName('h4')[0].innerText.toLowerCase();
        if (catName.includes(input)) {
            catItem.style.display = '';
            found = true;
        } else {
            catItem.style.display = 'none';
        }
    });

    notFound.style.display = found ? 'none' : 'block';
}
