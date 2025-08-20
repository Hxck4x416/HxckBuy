<?php
header('Content-Type: application/json');

// Archivo donde guardaremos keys + HWID
$keys_file = "keys.json";

// Si no existe el archivo, lo creamos vacío
if (!file_exists($keys_file)) {
    file_put_contents($keys_file, json_encode([]));
}

$keys_data = json_decode(file_get_contents($keys_file), true);

// Datos enviados desde Roblox
$data = json_decode(file_get_contents("php://input"), true);
$key = $data["key"] ?? "";
$hwid = $data["hwid"] ?? "";

if ($key == "" || $hwid == "") {
    echo json_encode(["success" => false, "message" => "Datos inválidos"]);
    exit;
}

// ¿La key existe en la lista?
if (!isset($keys_data[$key])) {
    echo json_encode(["success" => false, "message" => "Key inválida"]);
    exit;
}

// Si la key no tiene HWID asignado, lo registramos
if ($keys_data[$key] === null) {
    $keys_data[$key] = $hwid;
    file_put_contents($keys_file, json_encode($keys_data));
    echo json_encode(["success" => true, "message" => "Key activada en este dispositivo"]);
    exit;
}

// Si ya tiene HWID, comprobamos
if ($keys_data[$key] === $hwid) {
    echo json_encode(["success" => true, "message" => "Acceso autorizado"]);
} else {
    echo json_encode(["success" => false, "message" => "Key usada en otro dispositivo"]);
}
?>