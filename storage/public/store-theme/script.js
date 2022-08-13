searchForm = document.querySelector('.search-form');
if (document.querySelector('#search-btn')) {
    document.querySelector('#search-btn').onclick = () => {
        searchForm.classList.toggle('active');
    }
}