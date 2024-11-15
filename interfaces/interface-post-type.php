<?php

namespace MediaWolf\Interfaces;

interface PostTypeInterface {
    public static function register_post_type(): void;
    public static function get_post_type(): string;
}