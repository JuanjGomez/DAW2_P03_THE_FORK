document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imagen');
    const previewContainer = document.createElement('div');
    previewContainer.className = 'image-preview';
    
    // Insertar el contenedor de previsualización después del input de imagen
    imageInput.parentNode.insertBefore(previewContainer, imageInput.nextSibling);

    // Función para previsualizar la imagen
    imageInput.addEventListener('change', function(e) {
        previewContainer.innerHTML = ''; // Limpiar previsualización anterior
        
        const file = e.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '<p class="error-message">Por favor, selecciona un archivo de imagen válido.</p>';
            }
        } else {
            previewContainer.innerHTML = '<p>No se ha seleccionado ninguna imagen</p>';
        }
    });

    // Validación del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const precio = document.getElementById('precio_promedio').value;
        const nombre = document.getElementById('nombre_r').value;
        
        if (precio <= 0) {
            e.preventDefault();
            alert('El precio medio debe ser mayor que 0');
        }
        
        if (nombre.trim() === '') {
            e.preventDefault();
            alert('El nombre del restaurante es obligatorio');
        }
    });
}); 