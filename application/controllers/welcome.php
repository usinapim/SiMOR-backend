<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function get_niveles() {

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

           echo json_encode($retorno);
        } else {
            $this->response(array('status' => 'failed'), 400);
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */