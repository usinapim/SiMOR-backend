<?php

/**
 * Description of Usuario_model
 *
 * @author sergio
 */
class Usuario_model extends CI_Model {


    public function __construct() {
        $this->load->database();
    }

    public function getUsuarioPorEmail($email = '') {
        $email = strtoupper($email);
        $query = $this->db->query("SELECT * FROM ango_personas.usuario_datos_generales WHERE upper(nombre) = ?", array($email));
        $retorno = $query->result_array();
        return $retorno;
    }
    
    

    public function checkLogin($usuario = '', $password = '') {
        //$password = md5($password);
        //TODO ID DE DOMINIO y ROL
        $query = $this->db->query("SELECT pfd.id_persona , u.id_persona_federada personaFederada, pr.id_rol rol, u.id_usuario, di.nombre, di.onombre, di.apellido, di.oapellido  FROM ango_personas.usuario_datos_generales u 
            INNER JOIN ango_personas.persona_rol pr ON u.id_persona_federada = pr.id_persona_federada
            INNER JOIN ango_personas.persona_federacion pf ON u.id_persona_federada = pf.id_persona_federada
            INNER JOIN ango_personas.persona_federacion_dominio pfd ON pf.id_persona_federada = pfd.id_persona_federada    
            INNER JOIN ango_personas.dominio_interno di ON di.id_persona = pfd.id_persona    
            INNER JOIN ango_personas.usuarios_dominio ud ON ud.id_usuario = u.id_usuario    
            WHERE u.nombre = ? AND u.clave = ? AND u.estado = 'A' ", array($usuario, $password));
        $retorno = $query->result_array();
        return $retorno ? $retorno[0] : FALSE;
    }

}
