<!-- Category Panel Section -->
<section class="category-panel section-b-space m-0">
    <div class="container-fluid-lg">
        <div class="title text-center">
            <h2>Parcourir par catégorie</h2>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <div class="category-card" data-aos="fade-up" data-aos-delay="<?php echo (array_search($category, $categories) * 100); ?>">
                    <a href="categorie?category_name=<?php echo htmlspecialchars($category['nom_categorie']); ?>" class="cate-box">
                        <div class="category-image">
                            <img src="back-end/apps/uploads/<?php echo htmlspecialchars($category['image_cat']); ?>" alt="<?php echo htmlspecialchars($category['nom_categorie']); ?>">
                        </div>
                        <span class="category-name"><?php echo htmlspecialchars($category['nom_categorie']); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
</div></section>
        <!-- Filter Section -->


<style>
    /* Category Panel */
.category-panel {
    padding: 40px 0;
    background-color: #f8f9fa;
}

.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

.category-card {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.cate-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    text-decoration: none;
    padding: 15px;
}

.category-image {
    width: 100%;
    height: 150px;
    overflow: hidden;
    border-radius: 8px;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.05);
}

.category-name {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-top: 10px;
    text-transform: capitalize;
}

.form-select,
.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-select:focus,
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    outline: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .category-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .category-image {
        height: 120px;
    }

    .filter-container {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>