<?php

namespace Sthom\Back\Kernel\Framework;

use InvalidArgumentException;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use Sthom\Back\Kernel\Framework\Annotations\Orm\Entity;
use Sthom\Back\Kernel\Framework\Model\Exceptions\QueryExecutionException;
use Sthom\Back\Kernel\Framework\Model\Interfaces\EntityInterface;
use Sthom\Back\Kernel\Framework\Model\Model;
use Sthom\Back\Kernel\Framework\Model\SqlFn;
use Sthom\Back\Kernel\Framework\Utils\Logger;

/**
 * Classe abstraite AbstractRepository
 *
 * Fournit des fonctionnalités de base pour les repositories de l'application.
 */
abstract class AbstractRepository
{
    protected PDO $connection;
    protected SqlFn $sqlFn;
    protected LoggerInterface $logger;


    private string $entityClassname;

    private string $entityTable;

    /**
     * Constructeur.
     *
     * Initialise la connexion à la base de données et les fonctionnalités SQL.
     *
     */
    public function __construct()
    {
        $this->connection = Model::getInstance()->getConnection();
        $this->sqlFn = new SqlFn($this->connection);
        $this->init();
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
     * @return object|null Les données de l'entité ou null si non trouvée.
     */
    public final function find(int $id): ?object
    {
        try {
            $query = $this->customQuery()
                ->select($this->entityClassname)
                ->where('id', '=', $id)
                ->execute()[0] ?? [];
            return $this->hydrate($query) ?? null;
        } catch (PDOException $e) {
            Logger::error("Error finding entity with ID: {$id}", ['exception' => $e]);
            return null;
        }
    }

    /**
     * Trouve une entité par un champ spécifique.
     *
     * @param string $field Le champ à rechercher.
     * @param string $value La valeur du champ.
     * @return object|null Les données de l'entité ou null si non trouvée.
     */
    public final function findOneBy(string $field, string $value): ?object
    {
        try {
            $query = $this->customQuery()
                ->select($this->entityClassname)
                ->where($field, '=', $value)
                ->execute()[0] ?? [];

            return $this->hydrate($query) ?? null;
        } catch (PDOException $e) {
            Logger::error("Error finding entity by field: {$field} with value: {$value}", ['exception' => $e]);
            return null;
        }
    }

    /**
     * Trouve des entités par un champ spécifique.
     *
     * @param string $field Le champ à rechercher.
     * @param string $value La valeur du champ.
     * @return array Les données de l'entité.
     */
    public final function findBy(string $field, string $value): array
    {
        try {
            $result = $this->customQuery()
                ->select($this->entityClassname)
                ->where($field, '=', $value)
                ->execute();

            return array_map([$this, 'hydrate'], $result);
        } catch (PDOException $e) {
            Logger::error("Error finding entities by field: {$field} with value: {$value}", ['exception' => $e]);
            return [];
        }
    }

    /**
     *
     * Initialise le namespace de l'entité et le nom de la classe.
     *
     *
     * @return void
     */
    private function init(): void
    {
        $entityClassname = explode('\\', get_called_class());
        $this->entityClassname = str_replace('Repository', '', end($entityClassname));
        $this->entityClassname = "Sthom\\Back\\App\\Entity\\" . $this->entityClassname;
        try {
            $table = new \ReflectionClass($this->entityClassname);
            $this->entityTable = $table->getAttributes(Entity::class)[0]->getArguments()['table'] ?? '';
        } catch (\Exception $e) {
            Logger::error($e->getMessage());
        }
    }

    /**
     * Hydrate une entité avec des données.
     *
     * @param array $data Les données à hydrater.
     * @return object|null L'entité hydratée ou null en cas d'erreur.
     */
    private function hydrate(array $data): ?object
    {
        try {
            if (!class_exists($this->entityClassname)) {
                throw new InvalidArgumentException("Class {$this->entityClassname} does not exist");
            }
            return new $this->entityClassname($data);
        } catch (\Exception $e) {
            Logger::error("Error hydrating entity");
            return null;
        }
    }

    /**
     * Retourne toutes les entités.
     *
     * @return array La liste des entités.
     */
    public final function findAll(): array
    {
        try {
            $result = $this->customQuery()
                ->select($this->entityTable)
                ->execute();

            foreach ($result as $key => $value) {
                $result[$key] = $this->hydrate($value);
            }
            return $result;
        } catch (PDOException $e) {
            Logger::error("Error finding all entities", ['exception' => $e]);
            return [];
        }
    }

    /**
     * Sauvegarde une entité (création ou mise à jour).
     *
     * @param EntityInterface $data Les données de l'entité.
     * @return int L'identifiant de l'entité sauvegardée.
     */
    public final function save(EntityInterface $data): int
    {
        try {
            $data = $data->toArray();
            if (isset($data['id'])) {
                $this->customQuery()
                    ->update($this->entityClassname, $data, $data['id']);
                return $data['id'];
            } else {
                return $this->customQuery()
                    ->insert($this->entityClassname, $data);
            }
        } catch (QueryExecutionException $e) {
            Logger::error("Error saving entity", ['exception' => $e, 'data' => $data]);
            return 0; // Indicate failure
        }
    }

    /**
     * Supprime une entité par son identifiant.
     *
     * @param int $id L'identifiant de l'entité.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public final function delete(int $id): bool
    {
        try {
            return $this->customQuery()
                    ->delete($this->entityClassname, $id) > 0;
        } catch (QueryExecutionException $e) {
            Logger::error("Error deleting entity with ID: {$id}", ['exception' => $e]);
            return false;
        }
    }
}
