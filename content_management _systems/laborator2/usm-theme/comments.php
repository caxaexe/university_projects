<?php
if (have_comments()) :
    while (have_comments()) : the_comment();
?>
    <p><?php comment_text(); ?></p>
<?php
    endwhile;
endif;

comment_form();
?>