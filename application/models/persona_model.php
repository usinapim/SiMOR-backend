<?php

/**
 * Description of Usuario_model
 *
 * @author sergio
 */
class Persona_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getPreorden($id = '') {
        $query = $this->db->query("SELECT CONCAT (di.nombre,' ', di.onombre) nombre, CONCAT (di.apellido, ' ', di.oapellido) apellido, pf.tipo as tipoDocumento,
                                        pf.numero_documento as dni, pf.fecha_nacimiento fechaNacimiento, di.sexo as sexo, pd.descripcion insticucion 
                                    FROM ango_hc.pedidos_cabecera pc
                                    INNER JOIN ango_personas.dominio_interno di ON di.id_persona = pc.id_paciente
                                    INNER JOIN ango_personas.persona_federacion_dominio pfd ON pfd.id_persona = di.id_persona
                                    INNER JOIN ango_personas.persona_federacion pf ON pfd.id_persona_federada = pf.id_persona_federada
                                    INNER JOIN ango_personas.persona_dominio pd ON pd.id_dominio = pf.id_dominio
                            WHERE pc.id_pedido = ?", array($id));
        $retorno = $query->result_array();
        if ($retorno){
            $retorno[0]['nombre'] = trim($retorno[0]['nombre']);
            $retorno[0]['apellido'] = trim($retorno[0]['apellido']);
            return $retorno[0];
        }else{
            return false;
        }
        
    }

    public function esProductoECG($id = '') {
        $query = $this->db->query("SELECT * FROM ango_hc.resultado_cabecera WHERE id_pedido = ? 
                                        AND id_producto = 492", array($id));
        $retorno = $query->result_array();
        return $retorno ? TRUE : FALSE;
    }

    public function modificarConclusion($id = '', $conslusion = '') {
        $query = $this->db->query("SELECT rd.id as id FROM ango_hc.resultado_cabecera rc
                                        INNER JOIN ango_hc.resultado_detalle rd ON rc.id_resultado = rd.id_resultado
                                    WHERE rd.id_producto = 492
                                        AND rc.id_pedido = ?", array($id));
        $retorno = $query->result_array();
        $resultadoDetalle = $retorno ? $retorno[0] : FALSE;

        if ($resultadoDetalle) {
            $this->db->update('ango_hc.resultado_detalle', array(
                'resultado_de_texto' => strtoupper($conslusion)),
                array('id' => $resultadoDetalle['id']));
            
            return true;
        }else{
            return false;
        }
    }

}
