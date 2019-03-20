<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;

interface CartServiceInterface
{
    public function createCart(User $user);

}