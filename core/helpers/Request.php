<?php
class Request {
    function __construct(){}

    public function init(){
        global $user,$token;
        require_once './constantes.php';
        header('Content-Type: application/json; charset=UTF-8');

        $user = $_SERVER['PHP_AUTH_USER'];
        $token = $_SERVER['PHP_AUTH_PW'];
        // echo json_encode($_SERVER);die();
        
        $return = null;
        switch ($_REQUEST['action']) {
            case 'login': 
                $return = $this->iniciarSesion($_POST);
                break;
            case 'logout': 
                $return = $this->cerrarSesion();
                break;
            default:
                $return = $this->defaultAction( $_REQUEST );
                break;
        }
        echo json_encode($return);
        exit();
    }

    public function defaultAction( $params ) {
        global $user, $token;
        if (!empty($params)) { // si se han ingresado los parametros
            extract($params);  // $usuario, $app_key
            $arr_auth =  array(
                    "user" => $user,
                    "token" => $token,
            );
            $json_data = null;
            if ( !empty($action) ) {
                $url = "$controller?function=$action";
            } elseif ( !empty($function) ) {
                $url = "$controller?function=$function";
            } else {
                $url = "$controller";
            }
            if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
                unset($_GET['controller']);
                unset($_GET['function']);
                unset($_GET['action']);
                $url .= "&"; 
                $temp = implode('&', array_map(
                    function ($v, $k) { return sprintf("%s=%s", $k, $v); },
                    $_GET,
                    array_keys($_GET)
                ));
                $url .= $temp; 
            } else {
                unset($_REQUEST['controller']);
                unset($_REQUEST['function']);
                unset($_REQUEST['action']);
                $json_data = json_encode($_REQUEST);
            }
            // echo $url;
            // die();
            return $this->sendRequest($arr_auth, $url, $json_data, $_SERVER['REQUEST_METHOD']);
        }
    }

    public function iniciarSesion($param)
    {        
            // var_dump($param);die();
        if (!empty($param)) { // si se han ingresado los parametros
            extract($param);  // $usuario, $app_key
            $arr_auth =  array(
                    "user" => $user,
                    "token" => $token,
            );
            $json_data = array(
                    "username" => $user,
                    "password" => $password,
            );
            // var_dump($json_data);die();
            $rs = $this->sendRequest($arr_auth, "Usuario?function=login", json_encode($json_data));

            if (!empty($rs) && $rs['status'] == 200){ // si se encontraron permisos para el usuario
                $user = $rs['data'];
                // $permisos = $rs['permisos'];
                session_start();
                $_SESSION['last_access'] = time();    
                $_SESSION['name_session'] = PY_NAME;
                $_SESSION['inicio_sesion'] = date("Y-m-d H:i:s");       
                $_SESSION['id_user'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['nombre'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['token'] = $rs['token'];
                // $_SESSION['permisos'] = $permisos;
                return array("status" => "success", "message" => "Bienvenido ".$_SESSION['username']);

            } else { // el usuario no tiene permisos
                return array("status" => "empty", "message" => "El usuario ingresado no se ha encontrado");
            }
            
        }else{ // cuando el array param esta vacio
            return array("status" => "error", "message" => "Debe ingresar usuario y clave");
        }
    }
    public function cerrarSesion ()
    {
        session_start();
        $_SESSION = array();
        session_destroy();
        return array("status" => "success", "mensaje" => "Sesión finalizada");
    }

    public function sendRequest($arr_auth, $uri, $json_data = null, $http_method = 'POST') {
        $url = PATH.$uri;
        // echo $url . "<br>";
        // echo "<br>Inicio CURL: ".date("Y-m-d H:i:s");
        // die();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $arr_auth['user'].":" . $arr_auth['token']);
        if ( !empty($json_data) ) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($json_data)]);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400);
        if ( $http_method == 'POST' ) {
            curl_setopt($ch, CURLOPT_POST, 1);
        } else{
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method); // GET, PUT, DELETE
        }
        // curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false); // requerido a partir de PHP 5.6.0
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ( !empty($json_data) ) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $return_mc = json_decode($result, true);
        // echo "<br>FIN CURL: ".date("Y-m-d H:i:s");
        // var_dump($result);
        // echo $result;
        // var_dump($httpCode);
        // var_dump($return_mc);
        // die();
        if ($httpCode == 200) {
            return $return_mc;
        }
        return false;
    }
}

$obj = new Request();
$obj->init();