<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TipsRestController extends Controller
{
    public function getTipsAction() {
        $em      = $this->getDoctrine()->getManager();
        $tips = $em->getRepository( 'AppBundle:Tip' )->getUltimosTips();

        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        return array( 'tips' => $tips );
    }
}
