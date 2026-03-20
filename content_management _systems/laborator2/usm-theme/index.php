<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ТУТ</title>
</head>
<body>

<h1>Сидим тут</h1>

<?php get_header(); ?>

<div class="container">

    <main>
        <h2>Последние записи</h2>

        <?php
        if (have_posts()) :
            $count = 0;
            while (have_posts() && $count < 5) : the_post();
                $count++;
        ?>
            <article>
                <h3><?php the_title(); ?></h3>
                <p><?php the_excerpt(); ?></p>
            </article>
        <?php
            endwhile;
        endif;
        ?>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>


</body>
</html>