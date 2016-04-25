<?php

namespace AppBundle\Controller;

use Shaygan\TelegramBotApiBundle\TelegramBotApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use Symfony\Component\HttpFoundation\Request;

class NivelesRestController extends Controller {
	public function getNivelesAction() {
		$em      = $this->getDoctrine()->getManager();
		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findAll();

		header( 'Access-Control-Allow-Origin: *' );
		header( "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method" );
		header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );

		return array( 'puertos' => $puertos );
	}

	public function getNivelesPuertoAction( Request $request, $puerto ) {
		$em            = $this->getDoctrine()->getManager();
		$arrayCriteria = array( 'nombre' => $puerto );

		$puertos = $em->getRepository( 'AppBundle:Puerto' )->findOneBy( $arrayCriteria );

		$telegramApi = $this->container->get( 'shaygan.telegram_bot_api' );
		$chatId      = $request->get( 'chat_id' );
		$telegramApi->sendMessage( $chatId, 'buenoChau' );

//		// get the telegram api as a service
//		$api = $this->container->get('shaygan.telegram_bot_api');
//
//		$update = new Update();
//		$message = new Message();
////		$chatId= $api->sendChatAction($api->getMe()->getId());
//		$message->setText('Puerto el que lee');
//
//		$update->setMessage($message);
//
//		// test the API by calling getMe method
//		$user = $api->getMe();


		header( 'Access-Control-Allow-Origin: *' );
		header( "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method" );
		header( "Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE" );

		return $telegramApi->getMe();
	}


}
