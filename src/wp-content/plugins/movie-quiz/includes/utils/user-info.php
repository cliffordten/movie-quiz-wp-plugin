<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Ramsey\Uuid\Uuid;


class User_Info
{

    static function generate_user_id($existing_user_id)
    {

        if ($existing_user_id) {
            return $existing_user_id;
        }

        try {
            $uuid4 = Uuid::uuid4();
            return $uuid4->toString();
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage();
        }
    }
}