<?php
class proyectos{
    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;

    const NOMBRE_TABLA = "project";
    const ID_PROYECTO = "id";
    const NOMBRE = "nombre";
    const DESCRIPCION = "descripcion";
    const ENCARGADO = "encargado";
    const AREA = "area";

    public static function get($peticion){
        if (empty($peticion[0]))
            return (new proyectos())->obtenerProyectos();
        else
            return (new proyectos())->obtenerProyectos($peticion[0]);  
    }

    private function obtenerProyectos($idProyecto = NULL){
        try {
            if (!$idProyecto) {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA ;
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            } else {
                $comando = "SELECT * FROM " . self::NOMBRE_TABLA .
                    " WHERE " . self::ID_PROYECTO . "=?";
                // Preparar sentencia
                $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
                // Ligar idProyecto
                $sentencia->bindParam(1, $idProyecto, PDO::PARAM_INT);
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

    public static function post($peticion){
        $body = file_get_contents('php://input');
        $proyecto = json_decode($body);
        $idProyecto = (new proyectos())->crear($proyecto);
        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "Proyecto creado",
            "id" => $idProyecto
        ];
    }

    private function crear($proyecto){
        if ($proyecto) {
            try {
    
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
    
                // Sentencia INSERT
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                    self::NOMBRE . "," .
                    self::DESCRIPCION . "," .
                    self::ENCARGADO . "," .
                    self::AREA . ")" .
                    " VALUES(?,?,?,?)";
    
                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);
    
                $sentencia->bindParam(1, $nombre);
                $sentencia->bindParam(2, $descripcion);
                $sentencia->bindParam(3, $encargado);
                $sentencia->bindParam(4, $area);
    
    
                $nombre = $proyecto->nombre;
                $descripcion = $proyecto->descripcion;
                $encargado = $proyecto->encargado;
                $area = $proyecto->area;
    
                $sentencia->execute();
    
                // Retornar en el último id insertado
                return $pdo->lastInsertId();
    
            } catch (PDOException $e) {
                throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
            }
        } else {
            throw new ExcepcionApi(
                self::ESTADO_ERROR_PARAMETROS, 
                utf8_encode("Error en existencia o sintaxis de parámetros"));
        }
    }

    public static function put($peticion){
        if(!empty($peticion[0])){
            $idProyecto = $peticion[0];
            $body = file_get_contents('php://input');
            $proyecto = json_decode($body);
            if ((new proyectos())->modificar($idProyecto,$proyecto) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro modificado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El proyecto al que intentas acceder no existe", 404);
            }
        }
    }

    private function modificar($idProyecto,$proyecto){
        try {
            // Sentencia UPDATE
            $comando = "UPDATE " . self::NOMBRE_TABLA .
                " SET " . 
                self::NOMBRE . "=?," .
                self::DESCRIPCION . "=?," .
                self::ENCARGADO . "=?," .
                self::AREA . "=?" .
                " WHERE " . self::ID_PROYECTO . "=?";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $nombre);
            $sentencia->bindParam(2,$descripcion);
            $sentencia->bindParam(3, $encargado);
            $sentencia->bindParam(4,$area);
            $sentencia->bindParam(5, $idProyecto);

            $nombre = $proyecto->nombre;
            $descripcion = $proyecto->descripcion;
            $encargado = $proyecto->encargado;
            $area = $proyecto->area;

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    public static function delete($peticion){
        if (!empty($peticion[0])) {
            $idProyecto = $peticion[0];
            if ((new proyectos())->eliminar($idProyecto) > 0) {
                http_response_code(200);
                return [
                    "estado" => self::CODIGO_EXITO,
                    "mensaje" => "Registro eliminado correctamente"
                ];
            } else {
                throw new ExcepcionApi(self::ESTADO_NO_ENCONTRADO,
                    "El proyecto al que intentas acceder no existe", 404);
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_ERROR_PARAMETROS, "Falta id", 422);
        }
    }

    private function eliminar($idProyecto){
        try {
            // Sentencia DELETE
            $comando = "DELETE FROM " . self::NOMBRE_TABLA .
                " WHERE " . self::ID_PROYECTO . "=?";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $idProyecto);

            $sentencia->execute();

            return $sentencia->rowCount();

        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }
    
}
