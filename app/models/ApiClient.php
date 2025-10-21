<?php

class ApiClient
{
  public $apiSesionLogin;
  public $apiObtenerUsuarioToken;
  public $apiGetFacultad;
  public $apiGetCarrera;
  public $apiGetDocente;
  public $apiGetMenu;
  public $apiGetDetalleMenu;
  public $apiGetDbToken;
  public $apiDbMetodo;


  public $cantidadIntentos = 3;
  public $timeout = 400;
  public $milisegundosEspera = 1000;

  public function __construct()
  {

    $raizApi = 'http://179.60.136.116/SeguimientoAPI/api';
    $raizApiDb = 'https://servicioenlinea.ug.edu.ec/ServiciosGeneralesAPI/api';
    $this->apiSesionLogin = $raizApi . '/Sesion/Login'; 
    $this->apiObtenerUsuarioToken = $raizApi . '/GeneraToken/UsuarioToken';
    $this->apiGetFacultad = $raizApi . '/ConsultaDatos/GetFacultades';
    $this->apiGetCarrera = $raizApi . '/ConsultaDatos/GetCarreras';
    $this->apiGetMenu = $raizApi . '/ConsultaDatos/GetMenu';
    $this->apiGetDetalleMenu = $raizApi . '/ConsultaDatos/GetDetalleMenu';
    $this->apiGetDocente = $raizApi . '/ConsultaDatos/GetDocentes';
    $this->apiGetDbToken = $raizApiDb . '/Auth/login';
    $this->apiDbMetodo = $raizApiDb . '/AccesoDatos/GetDataSet';

  }

  /**
   * Realiza una llamada API genérica.
   * @param string $url URL del endpoint.
   * @param array $datos Datos a enviar (payload).
   * @param string|null $token Opcional. Token de autorización.
   * @return object|array Resultado de la API o detalles de error.
   */
  public function LlamadaApi($url, $datos, $token = null)
  {
    $ch = curl_init($url);

    $payload = json_encode($datos);
    
    $headers = [
      'Content-Type: application/json'
    ];

    if ($token) {

      $headers[] = 'Authorization: Bearer ' . $token;
    }


    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
 

    $respuesta = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($respuesta === false) {
        return [
            "success" => false,
            "mensaje" => "Error de cURL: $curlError",
            "httpCode" => $httpCode,
            "raw" => $respuesta
        ];
    }
    
    $respuestaJson = json_decode($respuesta, true); 
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "success" => false,
            "mensaje" => "Error al decodificar la respuesta JSON",
            "httpCode" => $httpCode,
            "raw" => $respuesta
        ];
    }

    return $respuestaJson;

    
      
  }

}