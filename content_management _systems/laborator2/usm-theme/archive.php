<?php get_header(); ?>

<div class="container">

    <main>
        <h1>Архив</h1>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article>
                <h3><?php the_title(); ?></h3>
                <p><?php the_excerpt(); ?></p>
            </article>
        <?php endwhile; endif; ?>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>