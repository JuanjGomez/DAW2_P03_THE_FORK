document.addEventListener('DOMContentLoaded', function() {
    const municipioSearch = document.querySelector('.municipio-search');
    const municipioSelect = document.querySelector('.municipio-filter select');

    municipioSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        Array.from(municipioSelect.options).forEach(option => {
            const text = option.text.toLowerCase();
            option.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });
}); 