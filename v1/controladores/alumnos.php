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
    const ESTADO_MODIFICACION_EXITOSA = 9;

    const ALUMNO_ELIMINADO = "Alumno eliminado correctamente";
    const ALUMNO_MODIFICADO = "";
    const ALUMNO_REGISTRADO = "¡Registro con éxito!";


    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;


    const NOMBRE_TABLA = "student";
    const ID = "id";
    const NOMBRE = "nombre";
    const APELLIDO_PATERNO = "apellidoPaterno";
    const APELLIDO_MATERNO = "apellidoMaterno";
    const NUMERO_DE_CONTROL = "numeroDeControl";
    const CLAVE_API = "claveApi";
    const ID_PROYECTO = "idProject";
    const CONTRASENA = "contrasena";

    const TABLA_PROYECTO = "project";
    const NOMBRE_PROYECTO = "nombre";

    public static function post($peticion){
        if ($peticion[0] == 'registro') {
            return (new alumnos())->registrar();
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }
    
    private function registrar(){
        $cuerpo = file_get_contents('php://input');
        $alumno = json_decode($cuerpo);

        $resultado = self::crear($alumno);

        switch ($resultado) {
            case self::ESTADO_CREACION_EXITOSA:
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_CREACION_EXITOSA,
                        "mensaje" => self::ALUMNO_REGISTRADO
                    ];
                break;
            case self::ESTADO_CREACION_FALLIDA:
                throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
                break;
            default:
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Falla desconocida", 400);
            }
    }
        
    private function crear($datosAlumno){
        $nombre = $datosAlumno->nombre;
        $apellidoPaterno = $datosAlumno->apellidoPaterno;
        $apellidoMaterno = $datosAlumno->apellidoMaterno;
        $numeroDeControl = $datosAlumno->numeroDeControl;
        $claveApi = self::generarClaveApi();
        $idProject = $datosAlumno->idProject;

        try {

            $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

            // Sentencia INSERT
            $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                self::NOMBRE . "," .
                self::APELLIDO_PATERNO . "," .
                self::APELLIDO_MATERNO . "," .
                self::NUMERO_DE_CONTROL . "," .
                self::CLAVE_API . "," .
                self::ID_PROYECTO . ")" .
                " VALUES(?,?,?,?,?,?)";

            $sentencia = $pdo->prepare($comando);

            $sentencia->bindParam(1, $nombre);
            $sentencia->bindParam(2, $apellidoPaterno);
            $sentencia->bindParam(3, $apellidoMaterno);
            $sentencia->bindParam(4, $numeroDeControl);
            $sentencia->bindParam(5, $claveApi);
            $sentencia->bindParam(6, $idProject);


            $resultado = $sentencia->execute();

            if ($resultado) {
                return self::ESTADO_CREACION_EXITOSA;
            } else {
                return self::ESTADO_CREACION_FALLIDA;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    public static function get($peticion){
        if(empty($peticion[0])){
            return (new alumnos())->obtenerAlumnos();
        }else{
            return (new alumnos())->obtenerAlumnos($peticion[0]);
        }
    }

    private function obtenerAlumnos($idAlumno = NULL){
        try {
            if(!$idAlumno){

                $sql = "SELECT" . 
                    " s." . self::NOMBRE .
                    " ,s." . self::APELLIDO_PATERNO .
                    " ,s." . self::APELLIDO_MATERNO .
                    " ,s." . self::NUMERO_DE_CONTROL . 
                    " ,p." . self::NOMBRE_PROYECTO . " AS nombreProyecto" .
                    " FROM " . self::NOMBRE_TABLA . " s " . 
                    " JOIN " . self::TABLA_PROYECTO . " p ";

                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            }else{
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID . "=?";
                
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idProyecto
                $sentencia->bindParam(1, $idAlumno, PDO::PARAM_INT);
            }

            // Ejecutar sentencia preparada
            if ($sentencia->execute()) {
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_EXITO,
                        "datos" => $sentencia->fetchAll(PDO::FETCH_ASSOC)
                    ];
            } else
                throw new ExcepcionApi(self::ESTADO_ERROR, "Se ha producido un error");

            
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    public static function put($peticion){
        $id = (new alumnos())->autorizar();
        $body = file_get_contents('php://input');
        $alumno = json_decode($body);
        if ((new alumnos())->actualizar($id,$alumno) > 0) {
            http_response_code(200);
            return [
                "estado" => self::ESTADO_MODIFICACION_EXITOSA,
                "mensaje" => "Registro actualizado correctamente"
            ];
        }else{
            throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
        }
    }

    private function actualizar($id,$alumno){
        $nombre = $alumno->nombre;
        $apellidoPaterno = $alumno->apellidoPaterno;
        $apellidoMaterno = $alumno->apellidoMaterno;
        $numeroDeControl = $alumno->numeroDeControl;
        $idProject = $alumno->idProject;

        try {
            // Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLA .
                " SET " . 
                self::NOMBRE . "=?," .
                self::APELLIDO_PATERNO . "=?," .
                self::APELLIDO_MATERNO . "=?," .
                self::NUMERO_DE_CONTROL . "=?," .
                self::ID_PROYECTO . "=?" .
                " WHERE " . self::ID . "=?";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            $sentencia->bindParam(1, $nombre);
            $sentencia->bindParam(2, $apellidoPaterno);
            $sentencia->bindParam(3, $apellidoMaterno);
            $sentencia->bindParam(4, $numeroDeControl);
            $sentencia->bindParam(5, $idProject);
            $sentencia->bindParam(6, $id);
            
            // Ejecutar la sentencia
            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }


    public static function delete($peticion){
        if (!empty($peticion[0])) {
            if((new alumnos())->eliminar($peticion[0]) > 0){
                http_response_code(200);
                return [
                    "estado" => self::ESTADO_EXITO,
                    "mensaje" => self::ALUMNO_ELIMINADO
                ];
            }else{
                throw new ExcepcionApi(self::ESTADO_PARAMETROS_INCORRECTOS,
                    "El alumno al que intentas acceder no existe", 404);
            }
            
        }else{
            throw new ExcepcionApi(self::ESTADO_ERROR, "Se requiere del id del alumno a eliminar");
        }
    }

    private function eliminar($idAlumno){
        try {
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID . "=?";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $idAlumno);

            $sentencia->execute();

            return $sentencia->rowCount();
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    private function generarClaveApi(){
        return md5(microtime().rand());
    }

    private function encriptarContrasena($contrasenaPlana){
        if ($contrasenaPlana){
            return password_hash($contrasenaPlana, PASSWORD_DEFAULT);
        }else{
            return null;
        }
    }

    private function autorizar(){
        $cabeceras = apache_request_headers();

        if (isset($cabeceras["Authorization"])) {

            $claveApi = $cabeceras["Authorization"];

            if ((new alumnos())->validarClaveApi($claveApi)) {
                return (new alumnos())->obtenerIdAlumno($claveApi);
            } else {
                throw new ExcepcionApi(
                    self::ESTADO_CLAVE_NO_AUTORIZADA, "Clave de API no autorizada", 401);
            }

        } else {
            throw new ExcepcionApi(
                self::ESTADO_AUSENCIA_CLAVE_API,
                utf8_encode("Se requiere Clave del API para autenticación")
            );
        }
    }

    private function validarClaveApi($claveApi){
        $comando = "SELECT COUNT(" . self::ID . ")" .
        " FROM " . self::NOMBRE_TABLA .
        " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        $sentencia->execute();

        return $sentencia->fetchColumn(0) > 0;
    }

    private function obtenerIdAlumno($claveApi){
        $comando = "SELECT " . self::ID .
        " FROM " . self::NOMBRE_TABLA .
        " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['id'];
        }else{
            return null;
        }
    }

    
}