<?php include '../includes/header.php'; ?>
<?php include '../cart/cart.php'; ?>

<main class="home-container">
    <div class="home-content">
        <h1><?= $translations['welcome'] ?> <span>House Burger</span></h1>
        
        <div class="hero-section">
            <div class="hero-text">
                <h2><?= $translations['hero_title'] ?></h2>
                <p><?= $translations['hero_subtitle'] ?></p>
                <a href="menu.php" class="btn-menu"><?= $translations['view_menu'] ?></a>
            </div>
            <div class="hero-image">
                <img src="../assets/images/burger-hero.jpg" alt="Hamburguesa House Burger">
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <img src="../assets/images/ingredientes.jpg" alt="Ingredientes naturales">
                <h3><?= $translations['feature_1_title'] ?></h3>
                <p><?= $translations['feature_1_desc'] ?></p>
            </div>
            <div class="feature-card">
                <img src="../assets/images/receta.jpg" alt="Receta secreta">
                <h3><?= $translations['feature_2_title'] ?></h3>
                <p><?= $translations['feature_2_desc'] ?></p>
            </div>
            <div class="feature-card">
                <img src="../assets/images/eco.jpg" alt="Compromiso ecológico">
                <h3><?= $translations['feature_3_title'] ?></h3>
                <p><?= $translations['feature_3_desc'] ?></p>
            </div>
        </div>
        
        <div class="testimonials">
            <h2><?= $translations['testimonials_title'] ?></h2>
            <div class="testimonial-card">
                <p><?= $translations['testimonial_1'] ?></p>
                <div class="client">- Pablo P.</div>
            </div>
            <div class="testimonial-card">
                <p><?= $translations['testimonial_2'] ?></p>
                <div class="client">- Tayri G.</div>
            </div>
        </div>
        
        <div class="cta-section">
            <h2><?= $translations['cta_title'] ?></h2>
            <a href="menu.php" class="btn-menu btn-large"><?= $translations['cta_button'] ?></a>
            <p><?= $translations['cta_call'] ?> <a href="tel:+123456789" class="phone-link">928 123 456</a></p>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
