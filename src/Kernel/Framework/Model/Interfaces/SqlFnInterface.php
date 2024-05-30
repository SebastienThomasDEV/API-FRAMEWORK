<?php

namespace Sthom\Back\Kernel\Framework\Model\Interfaces;

/**
 * Interface SqlFnInterface
 *
 * Fournit une interface pour construire et exécuter des requêtes SQL.
 */
interface SqlFnInterface
{
    /**
     * Sélectionne des champs dans une table.
     *
     * @param string $table Le nom de la table.
     * @param array $fields Les champs à sélectionner (par défaut, tous les champs).
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function select(string $table, array $fields = ['*']): SqlFnInterface;

    /**
     * Ajoute une condition WHERE à la requête.
     *
     * @param string $field Le champ à comparer.
     * @param string $operator L'opérateur de comparaison (par exemple, '=', '>', '<').
     * @param mixed $value La valeur à comparer.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function where(string $field, string $operator, mixed $value): SqlFnInterface;

    /**
     * Ajoute une condition AND WHERE à la requête.
     *
     * @param string $field Le champ à comparer.
     * @param string $operator L'opérateur de comparaison.
     * @param mixed $value La valeur à comparer.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function andWhere(string $field, string $operator, mixed $value): SqlFnInterface;

    /**
     * Ajoute une condition OR WHERE à la requête.
     *
     * @param string $field Le champ à comparer.
     * @param string $operator L'opérateur de comparaison.
     * @param mixed $value La valeur à comparer.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function orWhere(string $field, string $operator, mixed $value): SqlFnInterface;

    /**
     * Ajoute un tri à la requête.
     *
     * @param string $field Le champ à trier.
     * @param string $direction La direction du tri (ASC ou DESC).
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function orderBy(string $field, string $direction = 'ASC'): SqlFnInterface;

    /**
     * Limite le nombre de résultats retournés par la requête.
     *
     * @param int $limit Le nombre maximum de résultats.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function limit(int $limit): SqlFnInterface;

    /**
     * Définit l'offset des résultats retournés par la requête.
     *
     * @param int $offset Le décalage des résultats.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function offset(int $offset): SqlFnInterface;

    /**
     * Exécute la requête et retourne les résultats.
     *
     * @return array Les résultats de la requête.
     */
    public function execute(): array;

    /**
     * Insère des données dans une table.
     *
     * @param string $table Le nom de la table.
     * @param array $data Les données à insérer sous forme de tableau associatif.
     * @return int L'ID de la ligne insérée.
     */
    public function insert(string $table, array $data): int;

    /**
     * Met à jour des données dans une table.
     *
     * @param string $table Le nom de la table.
     * @param array $data Les données à mettre à jour sous forme de tableau associatif.
     * @param int $id L'ID de la ligne à mettre à jour.
     * @return int Le nombre de lignes affectées.
     */
    public function update(string $table, array $data, int $id): int;

    /**
     * Supprime une ligne dans une table.
     *
     * @param string $table Le nom de la table.
     * @param int $id L'ID de la ligne à supprimer.
     * @return int Le nombre de lignes affectées.
     */
    public function delete(string $table, int $id): int;

    /**
     * Ajoute une jointure à la requête.
     *
     * @param string $table Le nom de la table à joindre.
     * @param string $field1 Le champ de la table principale.
     * @param string $operator L'opérateur de comparaison.
     * @param string $field2 Le champ de la table jointe.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function join(string $table, string $field1, string $operator, string $field2): SqlFnInterface;

    /**
     * Ajoute une jointure gauche à la requête.
     *
     * @param string $table Le nom de la table à joindre.
     * @param string $field1 Le champ de la table principale.
     * @param string $operator L'opérateur de comparaison.
     * @param string $field2 Le champ de la table jointe.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function leftJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface;

    /**
     * Ajoute une jointure droite à la requête.
     *
     * @param string $table Le nom de la table à joindre.
     * @param string $field1 Le champ de la table principale.
     * @param string $operator L'opérateur de comparaison.
     * @param string $field2 Le champ de la table jointe.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function rightJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface;

    /**
     * Ajoute une jointure interne à la requête.
     *
     * @param string $table Le nom de la table à joindre.
     * @param string $field1 Le champ de la table principale.
     * @param string $operator L'opérateur de comparaison.
     * @param string $field2 Le champ de la table jointe.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function innerJoin(string $table, string $field1, string $operator, string $field2): SqlFnInterface;

    /**
     * Ajoute une clause GROUP BY à la requête.
     *
     * @param string $field Le champ par lequel grouper les résultats.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function groupBy(string $field): SqlFnInterface;

    /**
     * Ajoute une condition HAVING à la requête.
     *
     * @param string $field Le champ à comparer.
     * @param string $operator L'opérateur de comparaison.
     * @param mixed $value La valeur à comparer.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function having(string $field, string $operator, mixed $value): SqlFnInterface;

    /**
     * Compte le nombre de lignes correspondant à la requête.
     *
     * @return int Le nombre de lignes.
     */
    public function count(): int;

    /**
     * Calcule la somme d'un champ.
     *
     * @param string $field Le champ dont calculer la somme.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function sum(string $field): SqlFnInterface;

    /**
     * Calcule la moyenne d'un champ.
     *
     * @param string $field Le champ dont calculer la moyenne.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function avg(string $field): SqlFnInterface;

    /**
     * Calcule la valeur minimale d'un champ.
     *
     * @param string $field Le champ dont calculer la valeur minimale.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function min(string $field): SqlFnInterface;

    /**
     * Calcule la valeur maximale d'un champ.
     *
     * @param string $field Le champ dont calculer la valeur maximale.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function max(string $field): SqlFnInterface;

    /**
     * Sélectionne des valeurs distinctes d'un champ.
     *
     * @param string $field Le champ dont sélectionner les valeurs distinctes.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function distinct(string $field): SqlFnInterface;

    /**
     * Exécute une requête brute.
     *
     * @param string $query La requête SQL brute.
     * @return SqlFnInterface Retourne l'instance pour permettre le chaînage.
     */
    public function raw(string $query): SqlFnInterface;


}