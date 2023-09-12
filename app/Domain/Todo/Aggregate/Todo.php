<?php

namespace App\Domain\Todo\Aggregate;

use App\Domain\Todo\ValueObject\Id;
use App\Domain\Todo\ValueObject\Title;
use App\Domain\Todo\ValueObject\Content;
use App\Domain\Todo\ValueObject\Due;
use App\Domain\Shared\ValueObject\DateTimeValueObject;

final class Todo
{
    private function __construct(
        private Id $id,
        private Title $title,
        private Content $content,
        private Due $due,
        private DateTimeValueObject $createdAt,
        private ?DateTimeValueObject $updatedAt,
    ) {
    }

    public static function create(
        Id $id,
        Title $title,
        Content $content,
        Due $due,
        DateTimeValueObject $createdAt,
        ?DateTimeValueObject $updatedAt = null,
    ): self {
        return new self(
            $id,
            $title,
            $content,
            $due,
            $createdAt,
            $updatedAt,
        );
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function content(): Content
    {
        return $this->content;
    }

    public function due(): Due
    {
        return $this->due;
    }

    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeValueObject
    {
        return $this->updatedAt;
    }

    public function updateTitle(string $title): void
    {
        $this->title = Title::fromString($title);
    }

    public function updateContent(string $content): void
    {
        $this->content = Content::fromString($content);
    }

    public function updateDue(string $due): void
    {
        $this->due = Due::fromString($due);
    }

    public function asArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'title' => $this->title()->value(),
            'content' => $this->content()->value(),
            'due' => $this->due()->value(),
            'created_at' => $this->createdAt()->value(),
            'updated_at' => $this->updatedAt()?->value(),
        ];
    }
}
