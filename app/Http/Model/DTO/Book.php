<?php

namespace App\Http\Model\DTO;

class Book
{
    public function __construct(
      private readonly string $title,
      private readonly array $author,
      private readonly int $year,
      private readonly array $genre,
      private readonly string $description,
      private readonly int $isbn
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function genre(): array
    {
        return array_map('mb_strtolower', $this->genre);
    }

    public function description(): string
    {
        return $this->description;
    }

    public function isbn(): int
    {
        return $this->isbn;
    }

    public function author(): array
    {
        return $this->author;
    }

}