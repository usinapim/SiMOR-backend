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
use Shaygan\TelegramBotApiBundle\UpdateReceiver\UpdateReceiverInterface;


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

		$puertos           = $this->em->getRepository( 'AppBundle:Puerto' )->findAll();
		$unwanted_array    = array(
			'Š' => 'S',
			'š' => 's',
			'Ž' => 'Z',
			'ž' => 'z',
			'À' => 'A',
			'Á' => 'A',
			'Â' => 'A',
			'Ã' => 'A',
			'Ä' => 'A',
			'Å' => 'A',
			'Æ' => 'A',
			'Ç' => 'C',
			'È' => 'E',
			'É' => 'E',
			'Ê' => 'E',
			'Ë' => 'E',
			'Ì' => 'I',
			'Í' => 'I',
			'Î' => 'I',
			'Ï' => 'I',
			'Ñ' => 'N',
			'Ò' => 'O',
			'Ó' => 'O',
			'Ô' => 'O',
			'Õ' => 'O',
			'Ö' => 'O',
			'Ø' => 'O',
			'Ù' => 'U',
			'Ú' => 'U',
			'Û' => 'U',
			'Ü' => 'U',
			'Ý' => 'Y',
			'Þ' => 'B',
			'ß' => 'Ss',
			'à' => 'a',
			'á' => 'a',
			'â' => 'a',
			'ã' => 'a',
			'ä' => 'a',
			'å' => 'a',
			'æ' => 'a',
			'ç' => 'c',
			'è' => 'e',
			'é' => 'e',
			'ê' => 'e',
			'ë' => 'e',
			'ì' => 'i',
			'í' => 'i',
			'î' => 'i',
			'ï' => 'i',
			'ð' => 'o',
			'ñ' => 'n',
			'ò' => 'o',
			'ó' => 'o',
			'ô' => 'o',
			'õ' => 'o',
			'ö' => 'o',
			'ø' => 'o',
			'ù' => 'u',
			'ú' => 'u',
			'û' => 'u',
			'ý' => 'y',
			'þ' => 'b',
			'ÿ' => 'y',
			'(' => '_',
			')' => '_',
			' ' => '_'
		);
		$aPuertosConocidos = array();
		foreach ( $puertos as $puerto ) {
			$aPuertosConocidos[ '/' . strtr( $puerto->getNombre(),
				$unwanted_array ) ] = $puerto->getNombre();
		}

		$parseMode = null;
		switch ( $message['text'] ) {
			case array_key_exists( $message['text'], $aPuertosConocidos ):
				$arrayCriteria = array( 'nombre' => $aPuertosConocidos[ $message['text'] ] );
				$rio           = $this->em->getRepository( 'AppBundle:Puerto' )->findOneBy( $arrayCriteria );
				$text          = "*Nombre Río:* " . $rio->getNombreRio() . "\n";
				$text .= "*Medida último registro:* " . $rio->getMedidaUltimoRegistro() . "\n";
				$text .= "*Variación:* " . $rio->getMedidaVariacion() . "\n";
				$text .= "*Alerta:* " . $rio->getMedidaAlerta() . "\n";
				$text .= "*Evacuación:* " . $rio->getMedidaEvacuacion() . "\n";
				$text .= "*Estado Río:* " . $rio->getMedidaNombreEstadoRio();
				$parseMode = 'Markdown';
				break;
			case "/about":
			case "/acerca":
			case "/acerca@{$this->config['bot_name']}":
			case "/about@{$this->config['bot_name']}":
				$text      = "Bienvenidos al Bot del SiMOR! \n
				Descarga la app desde el PlayStore: [SiMOR](https://play.google.com/store/apps/details?id=org.pim.simor) \n
				Web: [FundacionPIM](http://fundacionpim.com.ar) \n
				";
				$parseMode = 'Markdown';
				break;
			case "/help":
			case "/ayuda":
			case "/help@{$this->config['bot_name']}":
			case "/ayuda@{$this->config['bot_name']}":
			default :
				$text = "Listado de Comandos:\n";
				$text .= "/about - Acerca de este bot\n";
				$text .= "/ayuda /help - Muestra este Mensaje\n";
				foreach ( $aPuertosConocidos as $key => $aPuertosConocido ) {
					$text .= "$key \n";
				}
				$text .= "para ver los estados\n";
				break;
		}

		$this->telegramBotApi->sendMessage( $message['chat']['id'], $text, $parseMode );
	}

}