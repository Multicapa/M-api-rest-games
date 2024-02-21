<?php

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = $_SERVER['REQUEST_URI'];

$json = file_get_contents('./assets/usuarios.json');
$usuarios = json_decode($json, true);

$messi = [
  "id"=>2,
  "nombre"=>"Lionel",
  "apellido"=>"Messi"
];

// $usuarios[] = $messi;

// Convertir el array de usuarios de nuevo a JSON
$usuariosJson = json_encode($usuarios);

//Conexion a base de datos
$server = 'localhost';
$usuario = 'root';
$password = 'root';
$baseDeDatos = 'api_rest_games';
$conexion = new mysqli($server, $usuario, $password, $baseDeDatos);

//Verificar la conexion
if ($conexion->connect_error) {
  echo 'No se pudo conectar a la base de datos.';
}

//Consulta sql
$sql = 'SELECT * FROM usuarios';
$consulta = $conexion->query($sql);
$usuario = json_encode($consulta->fetch_assoc());
// print_r($usuario);

switch ($metodo) {
  case 'GET':
    if ($ruta === '/proyectos/M-api-rest-games/usuarios') {
      //Convertir el array de usuarios a JSON
      $usuariosJSON = json_encode($usuarios);
      //Establecer las cabeceras
      header('Content-Type: application/json');
      echo $usuariosJSON;
    }
    break; 
  case 'POST':
    if ($ruta === '/proyectos/M-api-rest-games/api/user') {
      // $ruta2 = $_POST;
      $cuerpoPeticionJson = file_get_contents('php://input');
      $cuerpoPeticion = json_decode($cuerpoPeticionJson, true);

      foreach($usuarios as $user){
        if ($user['nombre'] === $cuerpoPeticion['nombre']){
          echo 'El usuario ya existe';
          exit;
        }
      }
  
      $usuarios[] = $cuerpoPeticion;
      $usuariosJSON = json_encode($usuarios);
  
      print_r($cuerpoPeticion);
      print_r($usuariosJSON);
      // Escribir la cadena JSON en el archivo
      file_put_contents('./clases/conexion/usuarios.json', $usuariosJSON);
      break;
    }
};

