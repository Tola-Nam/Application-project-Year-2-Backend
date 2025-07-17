<?php
require_once('./api_for_admin.php');

$api = new Api_for_admin();
$api->require_field(['first_name', 'last_name', 'email', 'gender', 'phone_number', 'password']);
$api->InsertAdmin(
    $api->input('first_name'),
    $api->input('last_name'),
    $api->input('email'),
    $api->input('gender'),
    $api->input('phone_number'),
    $api->input('password')
);

