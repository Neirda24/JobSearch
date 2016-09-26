<?php

namespace AppBundle\Repository;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface TokenStorageAwareRepository
{
    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage);
}
