<?php
/**
 * Created by PhpStorm.
 * User: matias
 * Date: 25/4/16
 * Time: 5:38 PM
 */

namespace AppBundle\UpdateReceiver;

use Shaygan\TelegramBotApiBundle\TelegramBotApi;
use Shaygan\TelegramBotApiBundle\Type\Update;


class UpdateReceiver implements UpdateReceiverInterface {

	private $config;
	private $telegramBotApi;
	private $em;

	public function __construct( TelegramBotApi $telegramBotApi, $config, $em ) {
		$this->telegramBotApi = $telegramBotApi;
		$this->config         = $config;
		$this->em             = $em;
	}

	public function handleUpdate( Update $update ) {
		$message = json_decode( json_encode( $update->message ), true );

		switch ( $message['text'] ) {
			case '/posadas':
				$arrayCriteria = array( 'nombre' => 'posadas' );
				$text          = $this->em->getRepository( 'AppBundle:Puerto' )->findOneBy( $arrayCriteria );
				break;
			case "/about":
			case "/acerca":
			case "/acerca@{$this->config['bot_name']}":
			case "/about@{$this->config['bot_name']}":
				$text = "Bienvenidos al Bot del SiMOR";
				break;
			case "/help":
			case "/ayuda":
			case "/help@{$this->config['bot_name']}":
			case "/ayuda@{$this->config['bot_name']}":
			default :
				$text = "Listado de Comandos:\n";
				$text .= "/about - Acerca de este bot\n";
				$text .= "/ayuda /help - Muestra este Mensaje\n";
				$text .= "/posadas - para ver el estado de posadas\n";
				break;
		}

		$this->telegramBotApi->sendMessage( $message['chat']['id'], $text );
	}

}