<footer class="footer">
    <!-- Incluye el enlace a Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <div class="footer-container">
        <!-- Sección de enlaces principales -->
        <div class="footer-section">
            <h3 class="footer-title">The Fork</h3>
            <ul class="footer-links">
                <li><a href="{{ route('principal') }}">Inicio</a></li>
                <li><a href="#">Sobre nosotros</a></li>
                <li><a href="#">Contacto</a></li>
                <li><a href="#">Trabaja con nosotros</a></li>
            </ul>
        </div>

        <!-- Sección de legal -->
        <div class="footer-section">
            <h3 class="footer-title">Legal</h3>
            <ul class="footer-links">
                <li><a href="#">Términos y condiciones</a></li>
                <li><a href="#">Política de privacidad</a></li>
                <li><a href="#">Política de cookies</a></li>
            </ul>
        </div>

        <!-- Sección de redes sociales -->
        <div class="footer-section">
            <h3 class="footer-title">Síguenos</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <!-- Sección de descarga de app -->
        <div class="footer-section app-download">
            <h3 class="footer-title">Descarga nuestra app</h3>
            <div class="app-buttons">
                <a href="#"><i class="fab fa-apple"></i> App Store</a>
                <a href="#"><i class="fab fa-google-play"></i> Google Play</a>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} The Fork. Todos los derechos reservados.</p>
    </div>
</footer> 