<?php

namespace Sthom\Back\repository;

use Sthom\Back\AbstractRepository;

class UserRepository extends AbstractRepository {

    public final function test() {
        return $this->queryBuilder()
            ->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', 1)
            ->execute();
    }
}
