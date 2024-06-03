<?php

namespace Sthom\Back\Kernel\Framework;

use PDO;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Model\SqlFn;

/**
 * Classe abstraite AbstractRepository
 *
 * Fournit des fonctionnalités de base pour les repositories de l'application.
 */
abstract class AbstractRepository
{
    protected PDO $connection;
    protected SqlFn $sqlFn;

    /**
     * Constructeur.
     *
     * Initialise la connexion à la base de données et les fonctionnalités SQL.
     */
    public function __construct()
    {
        $this->connection = Model::getInstance()->getConnection();
        $this->sqlFn = new SqlFn($this->connection);
    }

    /**
     * Retourne l'instance de SqlFn pour construire des requêtes SQL personnalisées.
     *
     * @return SqlFn Instance de SqlFn.
     */
    protected final function customQuery(): SqlFn
    {
        return $this->sqlFn;
    }

    /**
     * Trouve une entité par son identifiant.
     *
     * @param int $id L'identifiant de l'entité.
     * @return array|null Les données de l'entité ou null si non trouvée.
     */
    public final function find(int $id): ?object
    {
        $query = $this->customQuery()
            ->select($this->getTableName())
            ->where('id', '=', $id)
            ->execute()[0] ?? [];
        return $this->hydrate($query) ?? null;
    }

    /**
     * Trouve une entité par un champ spécifique.
     *
     * @param string $field Le champ à rechercher.
     * @param string $value La valeur du champ.
     * @return array|null Les données de l'entité ou null si non trouvée.
     */
    public final function findOneBy(string $field, string $value): ?object
    {
        $query = $this->customQuery()
            ->select($this->getTableName())
            ->where($field, '=', $value)
            ->execute()[0] ?? [];

        return $this->hydrate($query) ?? null;
    }

    /**
     * Trouve des entités par un champ spécifique.
     *
     * @param string $field Le champ à rechercher.
     * @param string $value La valeur du champ.
     * @return array|null Les données de l'entité ou null si non trouvée.
     */
    public final function findBy(string $field, string $value): ?array
    {

        $result =  $this->customQuery()
            ->select($this->getTableName())
            ->where($field, '=', $value)
            ->execute();

        $entities = [];
        foreach ($result as $data) {
            $entities[] = $this->hydrate($data);
        }
        return $entities;
    }

    public final function getEntityNamespace(): string
    {
        $entityClassname = explode('\\', get_called_class());
        $entityClassname = end($entityClassname);
        $entityClassname = str_replace('Repository', '', $entityClassname);
        return 'Sthom\Back\Entity\\' . $entityClassname;
    }


    private function hydrate(array $data): object
    {
        $entityClassname = $this->getEntityNamespace();
        $reflection = new \ReflectionClass($entityClassname);
        $entity = $reflection->newInstance();
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $property->setValue($entity, $data[$propertyName]);
        }
        return $entity;
    }



    /**
     * Retourne toutes les entités.
     *
     * @return array La liste des entités.
     */
    public final function findAll(): array
    {

        $result =  $this->customQuery()
            ->select($this->getTableName())
            ->execute();

        $entities = [];
        foreach ($result as $data) {
            $entities[] = $this->hydrate($data);
        }
        return $entities;
    }

    /**
     * Sauvegarde une entité (création ou mise à jour).
     *
     * @param array $data Les données de l'entité.
     * @return int L'identifiant de l'entité sauvegardée.
     */
    public final function save(object $data): int
    {
        $data = $data->toArray();
        if (isset($data['id'])) {
            $this->customQuery()
                ->update($this->getTableName(), $data, $data['id']);
            return $data['id'];
        } else {
            return $this->customQuery()
                ->insert($this->getTableName(), $data);
        }
    }

    /**
     * Supprime une entité par son identifiant.
     *
     * @param int $id L'identifiant de l'entité.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete(int $id): bool
    {
        return $this->customQuery()
                ->delete($this->getTableName(), $id) > 0;
    }




    /**
     * Retourne le nom de la table associée au repository.
     *
     * @return string Le nom de la table.
     */
    abstract protected function getTableName(): string;

    /**
     * Retourne le nom de la table associée au repository.
     *
     * @return string Le nom de la table.
     */
}
