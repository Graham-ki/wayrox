<?php
session_start();
header('Content-Type: text/plain');
echo "=== SESSION DEBUG ===\n\n";
echo "Session ID: " . session_id() . "\n";
echo "Session name: " . session_name() . "\n";
echo "Save path: " . session_save_path() . "\n\n";
echo "Session data:\n";
var_dump($_SESSION);
echo "\n\nCookies received:\n";
var_dump($_COOKIE);