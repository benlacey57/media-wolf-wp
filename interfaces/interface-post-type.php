<?php

namespace MediaWolf;

interface PostTypeInterface {
    public static function init(): void;
    public static function register(): void;
    public static function register_acf_fields(): void;
    public static function add_meta_boxes(): void;
    public static function customize_admin_columns(array $columns): array;
    public static function populate_admin_columns(string $column, int $post_id): void;
}