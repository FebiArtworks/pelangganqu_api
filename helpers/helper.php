<?php

function json_response($status, $message, $data = null) {
    header("Content-Type: application/json");
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit();
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function check_required_fields($fields, $source) {
    foreach ($fields as $field) {
        if (!isset($source[$field]) || empty(trim($source[$field]))) {
            json_response("error", "Field '$field' wajib diisi.");
        }
    }
}

function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

function get_post_json() {
    return json_decode(file_get_contents("php://input"), true);
}
?>