<?php

namespace Sthom\Back\entity;


use Sthom\Back\Annotations\db\Column;
use Sthom\Back\Annotations\db\ColumnType;
use Sthom\Back\Annotations\db\ManyToOne;
use Sthom\Back\Annotations\db\PrimaryKey;
use Sthom\Back\Annotations\Entity;
use Sthom\Back\repository\ArticleRepository;

#[Entity(name: 'articles', repository: ArticleRepository::class)]
class Article
{

    #[PrimaryKey('id', ColumnType::INT)]
    private ?int $id = null;

    #[Column('title', ColumnType::STRING)]
    private ?string $title = null;

    #[Column('content', ColumnType::STRING)]
    private ?string $content = null;

    #[ManyToOne(User::class, 'user_id')]
    private ?User $user_id = null;



    public final function getId(): ?int
    {
        return $this->id;
    }

    public final function getTitle(): ?string
    {
        return $this->title;
    }

    public final function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public final function getContent(): ?string
    {
        return $this->content;
    }

    public final function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }


}