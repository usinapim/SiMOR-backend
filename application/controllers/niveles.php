<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Niveles extends REST_Controller {

    private $rutaPdf = "/home/sergio/pdf/";

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('session');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }

        //$this->load->model('persona_model');
    }

//    function preorden_get() {
//
//        $id = $this->get('id');
//        if (!$id) {
//            $this->response(array('status' => 'failed'), 400);
//        }
//        if (!$this->persona_model->esProductoECG($id)) {
//            $this->response(array('status' => 'failed'), 400);
//        }
//
//        $data = $this->persona_model->getPreorden($id);
//
//        if ($data) {
//            $this->response($data, 200); // 200 being the HTTP response code
//        } else {
//            $this->response(array('status' => 'failed'), 400);
//        }
//    }
//    

    function get_niveles_get() {

        $datos = $this->getDatosTablaByURL("http://200.41.238.203:8889/resumen_agua.aspx", 1);
        //$datos = array ('id'=>2);

        if ($datos) {
            unset($datos [0]);
            unset($datos [1]);

            $retorno = array();
            foreach ($datos as $value) {
                $retorno[] = $value;
            }

            $retorno = array('puertos' => $retorno);

            $this->response(($retorno), 200); // 200 being the HTTP response code
        } else {
            $this->response(array('status' => 'failed'), 400);
        }
    }

    function preorden_pdf_post() {

        $id = $this->get('id');
        if (!$id) {
            $this->response(array('status' => 'failed'), 400);
        }
        if (!$this->persona_model->esProductoECG($id)) {
            $this->response(array('status' => 'failed'), 400);
        }

        $conclusiones = $this->post('conclusiones');

        if (!$this->persona_model->modificarConclusion($id, $conclusiones)) {
            $this->response(array('status' => 'failed'), 400);
        }

        $pdf_content = $this->post('data');
        //Decodifica el texto en base64
        $pdf_decoded = base64_decode($pdf_content);
        //Write data back to pdf file
        $pdf = fopen("$this->rutaPdf pdf_$id.pdf", 'w');
        fwrite($pdf, $pdf_decoded);
        //close output file
        $close = fclose($pdf);



        if ($close === FALSE) {
            $this->response(array('status' => 'failed'), 400);
        } else {
            $this->response(array('status' => 'success'), 200);
        }
    }

    /**
     * 
     * @param type $url url en la cual se encuetra la tabla
     * @param type $posTabla posicion en la cual se encuetra la tabla dentro del DOC
     */
    private function getDatosTablaByURL($url, $posTabla = 0) {
        $dom = new DOMDocument();

//load the html  
        $html = $dom->loadHTMLFile($url);

        //discard white space   
        $dom->preserveWhiteSpace = false;

        //the table by its tag name  
        $tables = $dom->getElementsByTagName('table');


        //get all rows from the table  
        $rows = $tables->item($posTabla)->getElementsByTagName('tr');
        // get each column by tag name  
        $cols = $rows->item(0)->getElementsByTagName('th');
        $row_headers = NULL;
        foreach ($cols as $node) {
            //print $node->nodeValue."\n";   
            $row_headers[] = $node->nodeValue;
        }

        $table = array();
        //get all rows from the table  
        $rows = $tables->item($posTabla)->getElementsByTagName('tr');
        foreach ($rows as $row) {
            // get each column by tag name  
            $cols = $row->getElementsByTagName('td');
            $row = array();
            $i = 0;
            foreach ($cols as $node) {
                # code...
                //print $node->nodeValue."\n";   
                if ($row_headers == NULL)
                    $row[] = $node->nodeValue;
                else {
                    $indice = $this->normaliza($row_headers[$i]);

                    $row[$indice] = $node->nodeValue;
                }
                $i++;
            }
            $table[] = $row;
        }
        return $table;
    }

    function normaliza($cadena) {
//        $charset='ISO-8859-1'; // o 'UTF-8'
//        $str = iconv($charset, 'ASCII//TRANSLIT', $str);
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

}

?>