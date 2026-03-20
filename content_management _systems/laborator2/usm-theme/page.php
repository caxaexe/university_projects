<?php get_header(); ?>

<div class="container">

    <main>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h1><?php the_title(); ?></h1>
                <p><?php the_content(); ?></p>
            </article>
        <?php endwhile; ?>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>