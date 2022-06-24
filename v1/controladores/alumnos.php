<?php
class alumnos{
    const ESTADO_CREACION_EXITOSA = 1;
    const ESTADO_CREACION_FALLIDA = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_AUSENCIA_CLAVE_API = 4;
    const ESTADO_CLAVE_NO_AUTORIZADA = 5;
    const ESTADO_URL_INCORRECTA = 6;
    const ESTADO_FALLA_DESCONOCIDA = 7;
    const ESTADO_PARAMETROS_INCORRECTOS = 8;

    const NOMBRE_TABLA = "student";
    const ID = "id";
    const NOMBRE = "nombre";
    const APELLIDO_PATERNO = "apellidoPaterno";
    const APELLIDO_MATERNO = "apellidoMaterno";
    const NUMERO_DE_CONTROL = "numeroDeControl";
    const CLAVE_API = "claveApi";
    const CONTRASENA = "contrasena";

    public static function post($peticion){
        if ($peticion[0] == 'registro') {
            return (new alumnos())->registrar();
        } else if ($peticion[0] == 'login') {
            return (new alumnos())->loguear();
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }

    private function registrar(){
        echo "registro";
    }

    private function loguear(){
        echo "loguear";
    }
}