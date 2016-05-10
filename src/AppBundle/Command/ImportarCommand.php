<?php
namespace AppBundle\Command;

use RMS\PushNotificationsBundle\Message\AndroidMessage;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportarCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rio:importar:prefectura')
            ->setDescription('Importa datos desde prefectura')
//            ->addArgument(
//                'name',
//                InputArgument::OPTIONAL,
//                'Who do you want to greet?'
//            )
//            ->addOption(
//                'yell',
//                null,
//                InputOption::VALUE_NONE,
//                'If set, the task will yell in uppercase letters'
//            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $datos = $this->getContainer()->get('manager.rios')->getDatosPrefectura();
        if ($datos) {
            $em = $this->getContainer()->get('doctrine')->getManager();

            $output->writeln('Datos de Prefectura importados Correctamente');
            $message = new AndroidMessage();
            $aSubscribers = $em->getRepository('AppBundle:Subscriptor')->findAll();

            foreach ($aSubscribers as $aSubscriber) {
                $message->addGCMIdentifier($aSubscriber->getDeviceId());
            }
            $message->setData(array('title' => 'SiMOR'));
            $message->setMessage('Hemos actualizados nuestros datos. Entra a mirarlos');
            $message->setGCM(true);
            $this->getContainer()->get('rms_push_notifications')->send($message);

        } else {
            $output->writeln('Ocurrió un error al procesar Datos de Prefectura');

        }


    }
}