<?php
header("Content-Type: application/json; charset=utf-8");

echo json_encode([
  "status" => "OK",
  "message" => "API is running"
]);
