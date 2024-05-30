<?php

namespace Sthom\Back\Repository;

use Sthom\Back\Kernel\Framework\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     *
     * Dans cette classe, nous allons écrire des requêtes personnalisées pour les utilisateurs.
     * Voir le fichier SqlFn.php pour plus de détails sur les requêtes.
     *
     * Dans cet exemple, nous allons écrire une méthode pour récupérer les utilisateurs par rôle.
     *
     * en sql : SELECT * FROM user WHERE roles LIKE '%ROLE_ADMIN%'
     *
     */

    public final function findByRole(string $role): array
    {
        return $this->customQuery()
            ->select("user")
            ->where("roles", "LIKE", "%$role%")
            ->execute();
    }

    protected function getTableName(): string
    {
        return "user";
    }

}