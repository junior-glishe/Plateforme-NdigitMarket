<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['email_progress'])) {
    echo json_encode($_SESSION['email_progress']);
} else {
    echo json_encode(['status' => 'aucun', 'sent' => 0, 'total' => 0]);
}