<?php

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = $_SERVER['REQUEST_URI'];

//Conexion a base de datos
$server = 'localhost';
$usuario = 'root';
$password = 'root';
$baseDeDatos = 'api_rest_games';
$conexion = new mysqli($server, $usuario, $password, $baseDeDatos);

//Verificar la conexion
if ($conexion->connect_error) {
  echo 'No se pudo conectar a la base de datos.';
  exit;
}

//Consulta sql
$sql = 'SELECT * FROM usuarios';
$consultaUsuarios = $conexion->query($sql);

switch ($metodo) {
  case 'GET':
    if ($ruta === '/proyectos/M-api-rest-games/usuarios') {

      $users = [];
      while($row = $consultaUsuarios->fetch_assoc()) {
        $users[] = $row;
      }
      //Convertir el array de usuarios a JSON
      $usuariosJSON = json_encode($users);
      //Establecer las cabeceras
      header('Content-Type: application/json');
      echo $usuariosJSON;
    }
    break; 
  case 'POST':
    if ($ruta === '/proyectos/M-api-rest-games/api/user') {
      
      //Recuperando el cuerpo de la peticion
      $cuerpoPeticionJson = file_get_contents('php://input');
      $cuerpoPeticion = json_decode($cuerpoPeticionJson, true);

      $nombre = $cuerpoPeticion['nombre'];
      $contrasena = $cuerpoPeticion['contrasena'];
      //Encriptando contrasena
      $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);
      
      $users = [];
      while($row = $consultaUsuarios->fetch_assoc()) {
        $users[] = $row;
      }
      
      //Verificando si el usuario ya existe
      foreach($users as $user){
        if ($user['nombre'] === $nombre){
          echo 'El usuario ya existe';
          exit;
        }
      }

      //Insertar nuevo usuario a la base de datos
      $conexion->query("INSERT INTO `api_rest_games`.`usuarios` (`nombre`, `contrasena`) VALUES ('$nombre', '$contrasena_encriptada');");

      echo "El usuario '$nombre' fue registrado correctamente";
      print_r($cuerpoPeticion);

    }
    break;
};

