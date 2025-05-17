<?php include '../includes/header.php'; ?>
<?php include '../cart/cart.php'; ?>

<main class="home-container">
    <div class="home-content">
        <h1>Bienvenidos a <span>House Burger</span></h1>
        
        <div class="hero-section">
            <div class="hero-text">
                <h2>Las hamburguesas más auténticas de la ciudad</h2>
                <p>Desde 2010 sirviendo sabores que enamoran</p>
                <a href="menu.php" class="btn-menu">Ver nuestra carta</a>
            </div>
            <div class="hero-image">
                <img src="../assets/images/burger-hero.jpg" alt="Hamburguesa House Burger">
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <img src="../assets/images/ingredientes.jpg" alt="Ingredientes naturales">
                <h3>Ingredientes 100% naturales</h3>
                <p>Carne de primera calidad, vegetales frescos y pan artesano hecho diariamente</p>
            </div>
            <div class="feature-card">
                <img src="../assets/images/receta.jpg" alt="Receta secreta">
                <h3>Nuestra receta secreta</h3>
                <p>Salsas caseras y mezcla de especias exclusiva que hacen la diferencia</p>
            </div>
            <div class="feature-card">
                <img src="../assets/images/eco.jpg" alt="Compromiso ecológico">
                <h3>Compromiso ecológico</h3>
                <p>Envases biodegradables y proveedores locales para reducir nuestra huella</p>
            </div>
        </div>
        
        <div class="testimonials">
            <h2>Lo que dicen nuestros clientes</h2>
            <div class="testimonial-card">
                <p>"Las mejores hamburguesas que he probado. La carne es jugosa y los ingredientes frescos hacen toda la diferencia."</p>
                <div class="client">- Pablo P.</div>
            </div>
            <div class="testimonial-card">
                <p>"El ambiente es increíble y el servicio impecable. Mis hijos piden venir todos los fines de semana."</p>
                <div class="client">- Tayri G.</div>
            </div>
        </div>
        
        <div class="cta-section">
            <h2>¿Listo para una experiencia gastronómica única?</h2>
            <a href="menu.php" class="btn-menu btn-large">Ver menú completo</a>
            <p>O si prefieres, <a href="tel:+123456789" class="phone-link">llámanos al 928 123 456</a></p>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>