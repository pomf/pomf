<?php
session_start();

if (isset($_SESSION['id'])) {
    header('Location: includes/api.php?do=cp');
} else {
    header('Location: login/');
}
