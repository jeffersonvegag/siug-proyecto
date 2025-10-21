<?php

require_once "../app/models/ApiClient.php";
require_once "../app/models/InstitucionService.php";

class InicioSesionModel
{
    private $ApiClient;
    private $instServ;

    public function __construct()
    {
        $this->ApiClient = new ApiClient();
        $this->instServ = new InstitucionService();
    }

    public function loginConToken($username, $password)
    {
        
      // Paso 2: preparar datos del usuario real
      $token = $this->instServ->GetToken();  
      $loginData = [
            "ip"          => "0.0.0.0",
            "username"    => $username,
            "password"    => $password,
            "tipoUsuario" => "I"
        ];

        $response = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiSesionLogin,
            $loginData,
            $token
        );

        return $response;
    }
}
