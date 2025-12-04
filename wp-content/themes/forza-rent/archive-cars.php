<main class="cars">
    <div class="container">
        <h1>All cars</h1>

<div class="class-picker">
    <p class="car-category active">All</p>
    <p class="car-category">Economy</p>
    <p class="car-category">Suv</p>
    <p class="car-category">Sport</p>
    <p class="car-category">Luxary</p>
    <p class="car-category">Van</p>
    <p class="car-category">Motorcicles</p>
    <p class="car-category">Quad</p>
</div>

<div class="advanced-filters">
    <div class="filter-group">
        <label for="fuelFilter">Fuel type</label>
        <select id="fuelFilter">
            <option value="">Any</option>
            <option value="petrol">Petrol</option>
            <option value="diesel">Diesel</option>
            <option value="electric">Electric</option>
            <option value="hybrid">Hybrid</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="gearboxFilter">Gearbox</label>
        <select id="gearboxFilter">
            <option value="">Any</option>
            <option value="manual">Manual</option>
            <option value="automatic">Automatic</option>
        </select>
    </div>

    <div class="filter-group price-group">
    <label for="priceRange">Price</label>
    <div class="price-slider">
        <input type="range"
               id="priceRange"
               min="0"
               max="200"
               step="5"
               value="200" />
        <span class="price-label">Max. $200.00</span>
    </div>
</div>

</div>
    <div class="grid">
        <?php
        $cars_query = new WP_Query([
            'post_type'      => 'cars',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ]);

        if ( $cars_query->have_posts() ) :
            while ( $cars_query->have_posts() ) : $cars_query->the_post();
                get_template_part('template-parts/cars/car');
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No cars found.</p>';
        endif;
        ?>
    </div>
    <div class="cars-actions">
        <button id="loadMoreCars" class="load-more-btn">Show more</button>
    </div>
</div>
</main>
<?php get_footer(); ?>