<?php


const CONTROLLER_TEMPLATE = <<<PHP
<?php
namespace Sthom\Back\controller;

use Sthom\Back\AbstractController;
use Sthom\Back\Annotations\Route;

class @Controller extends AbstractController
{

    #[Route(path: '/', method: 'GET')]
    public final function index(): array
    {
        %message = 'Hello World';
        return %this->send([
            'message' => %message
        ]);
    }

}
PHP;


const PROPERTIES_TEMPLATE = <<<PHP
    #[Column('@name', ColumnType::@CHANGE_ME)]
    private ?@type %@name = null;
PHP;

const GETTER_TEMPLATE = <<<PHP
    public final function get@Name(): ?@type {
        return %this->@name;
    }
PHP;

const SETTER_TEMPLATE = <<<PHP
    public final function set@Name(@type %@name): self {
        %this->@name = $@name;
        return %this;
    }
PHP;


const ENTITY_TEMPLATE = <<<PHP
<?php

namespace Sthom\Back\\entity;

use Sthom\Back\Annotations\db\Column;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\Database\AbstractEntity;
use Sthom\Back\\repository\@Repository;

#[Entity(name: '@table', repository: @Repository::class)]
class @Entity extends AbstractEntity
{
    // Multiple annotations are not supported yet
    // Here the annotation is that is supported
    // - PrimaryKey
    // - Column
    // - ManyToOne
    // - OneToMany
    // - ManyToMany
    
    #[PrimaryKey('id', ColumnType::INT)]
    private ?int %id = null;
    
@properties

    public final function getId(): ?int
    {
        return %this->id;
    }
    
@getters
    
@setters
}
PHP;


const REPOSITORY_TEMPLATE = <<<PHP
<?php

namespace Sthom\Back\\repository;

use Sthom\Back\AbstractRepository;

class @Repository extends AbstractRepository {}
PHP;




