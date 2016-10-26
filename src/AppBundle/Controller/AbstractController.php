<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;

abstract class AbstractController extends Controller
{
    /**
     * Message can be a translation key.
     *
     * {@inheritdoc}
     */
    protected function addFlash($type, $message, array $parameters = [], string $domain = 'messages')
    {
        $message = $this->get('translator')->trans($message, $parameters, $domain);

        parent::addFlash($type, $message);
    }

    /**
     * @param string $key
     * @param array  $parameters
     * @param string $domain
     *
     * @return FormError
     */
    protected function createFormError(string $key, array $parameters = [], string $domain = 'validators')
    {
        $message = $this->get('translator')->trans($key, $parameters, $domain);

        return new FormError($message);
    }
}
