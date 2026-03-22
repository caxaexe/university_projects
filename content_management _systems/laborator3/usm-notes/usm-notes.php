<?php

/*
Plugin Name: usm notes
Description: ну это полезный плагин да
Version: 1.0
Author: я
*/

function usm_register_notes_cpt() {
    $labels = array(
        'name'               => 'Заметки',
        'singular_name'      => 'Заметка',
        'menu_name'          => 'Заметки',
        'name_admin_bar'     => 'Заметка',
        'add_new'            => 'Добавить новую',
        'add_new_item'       => 'Добавить новую заметку',
        'new_item'           => 'Новая заметка',
        'edit_item'          => 'Редактировать заметку',
        'view_item'          => 'Просмотреть заметку',
        'all_items'          => 'Все заметки',
        'search_items'       => 'Искать заметки',
        'not_found'          => 'Заметки не найдены',
        'not_found_in_trash' => 'В корзине заметок нет',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-edit-page',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
        'show_in_rest'       => true,
        'rewrite'            => array( 'slug' => 'notes' ),
    );

    register_post_type( 'usm_note', $args );
}
add_action( 'init', 'usm_register_notes_cpt' );