document.addEventListener('DOMContentLoaded', function() {
    const priceSlider = document.getElementById('price-slider');
    const minPrice = document.getElementById('min-price');
    const maxPrice = document.getElementById('max-price');

    priceSlider.addEventListener('input', function() {
        minPrice.textContent = this.value;
        maxPrice.textContent = 100 - this.value;
    });
}); 