<?php

namespace AppBundle\Command\Debug;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:debug:sendmail')
            ->setDescription('Send an email as test.')
            ->addArgument('to', InputArgument::REQUIRED);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $body = <<<HTML
<html>
    <body>
        Test successfull
    </body>
</html>
HTML;
        
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom($this->getParameter('mailer_user'))
            ->setTo($input->getArgument('to'))
            ->setBody($body, 'text/html')
            ->addPart('Test successfull', 'text/plain');
        $this->get('mailer')->send($message);
        
        $output->writeln('<info>Check your emails.</info>');
        
        return 0;
    }
    
    /**
     * @param string $service
     *
     * @return object
     */
    private function get($service)
    {
        return $this->getContainer()->get($service);
    }
    
    /**
     * @param string $parameter
     *
     * @return object
     */
    private function getParameter($parameter)
    {
        return $this->getContainer()->getParameter($parameter);
    }
}
