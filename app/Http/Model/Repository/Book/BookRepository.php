<?php

namespace App\Http\Model\Repository\Book;

use App\App;
use App\Core\Database\DatabaseInterface;
use App\Core\Database\Insert\InsertQueryBuilder;
use App\Core\Database\Select\SelectQueryBuilder;
use App\Http\Model\DTO\Book;

class BookRepository implements BookRepositoryInterface
{

    public function __construct(
      private readonly DatabaseInterface $database
    ) {}

    public function insertBook(Book $bookData): bool
    {
        $data = [
          'title' => $bookData->title(),
          'year' => $bookData->year(),
          'isbn' => $bookData->isbn(),
        ];

        $bookId = $this->insertData('Book', $data);

        if ($bookId) {
            $genres = $bookData->genre();
            $authors = $bookData->author();

            $this->insertGenresWithRelation($bookId, $genres);

            $this->insertAuthorsWithRelation($bookId, $authors);
            return true;
        }
        return false;
    }

    protected function insertRelation(string $tableName, array $values_assoc): void
    {
        $sql = InsertQueryBuilder::table($tableName)
          ->values(array_keys($values_assoc))->getQuery();

        $this->database->execute($sql, $values_assoc);
    }

    /**
     * @param  string  $tableName
     * @param  string  $selectColumn
     * @param  string  $conditionColumn
     * @param  array  $values
     *
     * Firstly, method determines whether some tables have data that already exists, if so,
     * return an array of all identifiers found by "selectColumn" field, if not,
     * insert all new values and return an array of their id's
     *
     * @return array
     */
    protected function findOrCreateEntity(string $tableName, string $selectColumn, string $conditionColumn, array $values): array
    {
        $existingEntities = [];
        $newEntities = [];

        foreach ($values as $value) {
            $findSameGenreSQL = SelectQueryBuilder::table($tableName)
              ->select($selectColumn)
              ->where($conditionColumn, '=', 'value')
              ->getQuery();

            $stmt = $this->database->execute($findSameGenreSQL,
              [':value' => $value]);
            $existingEntity = $stmt->fetch();

            if ($existingEntity) {
                $existingEntities[$value] = $existingEntity[$selectColumn];
            } else {
                $newEntities[] = $value;
            }
        }
        if ($newEntities) {
            $saveGenreSQL = InsertQueryBuilder::table($tableName)
              ->values([$conditionColumn])->getQuery();

            foreach ($newEntities as $newValue) {
                $stmt = $this->database->execute($saveGenreSQL,
                  [":".$conditionColumn => $newValue]);
                $existingEntities[] = $this->database->lastInsertedId();
            }
        }

        return $existingEntities;
    }

    protected function insertData(string $tableName, array $data): int
    {
        $sql = InsertQueryBuilder::table($tableName)
          ->values(array_keys($data))
          ->getQuery();

        $stmt = $this->database->execute($sql, $data);

        return $stmt ? $this->database->lastInsertedId() : 0;
    }

    private function insertGenresWithRelation(int $bookId, array $genres): void
    {
        $genresId = $this->findOrCreateEntity('Genre', 'genre_id', 'genre_name', $genres);
        foreach ($genresId as $genreId) {
            $this->insertRelation('Book_Genre', ['book_id' => $bookId, 'genre_id' => $genreId]);
        }
    }

    private function insertAuthorsWithRelation(int $bookId, array $authors): void
    {
        $authorsId = $this->findOrCreateEntity('Author', 'author_id', 'name', $authors);
        foreach ($authorsId as $authorId) {
            $this->insertRelation('Book_Author', ['book_id' => $bookId, 'author_id' => $authorId]);
        }
    }
}