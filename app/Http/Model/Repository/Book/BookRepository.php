<?php

namespace App\Http\Model\Repository\Book;

use App\Core\Database\DatabaseInterface;
use App\Core\Database\DeleteQueryBuilder\DeleteQueryBuilder;
use App\Core\Database\Insert\InsertQueryBuilder;
use App\Core\Database\Select\SelectQueryBuilder;
use App\Http\Model\DTO\Book;

class BookRepository implements BookRepositoryInterface
{

    public function __construct(
      private readonly DatabaseInterface $database
    ) {}

    public function findBooks(int $limit = 0, int $offset = 0, mixed $book_id = null, mixed $user_id = null): array
    {
        $sql = SelectQueryBuilder::table("\"Book\"", 'B')
          ->select('B.book_id', 'B.title', 'B.year', 'B.isbn',
            "string_agg(DISTINCT Genre.genre_name, ', ') AS genre", "string_agg(DISTINCT Author.name, ', ') AS author",
            'B.description')
          ->join("\"Book_Genre\"", 'book_id', '=', 'B.book_id')
          ->join("\"Book_Author\"", 'book_id', '=', 'B.book_id')
          ->join("\"Genre\"", 'genre_id', '=', "\"Book_Genre\".genre_id")
          ->join("\"Author\"", 'author_id', '=', "\"Book_Author\".author_id");

        if($book_id !== null && $user_id !== null){
            return $this->getOneFavoriteBookById($sql, $book_id, $user_id);
        }
        if($user_id !== null){
            return $this->getAllFavoriteBooks($sql, $user_id, $limit, $offset);
        }
        if ($book_id !== null) {
            return $this->getBookById($sql, $book_id, $limit, $offset);
        }

        return $this->getAllBooks($sql, $limit, $offset);
    }

    public function insertBook(Book $bookData): bool|string
    {
        $data = [
          'title' => $bookData->title(),
          'year' => $bookData->year(),
          'isbn' => $bookData->isbn(),
          'description' => $bookData->description(),
        ];

        $genres = $bookData->genre();
        $authors = $bookData->author();

        $this->database->beginTransaction();
        try {
            $bookId = $this->insertData("\"Book\"", $data);

            $this->insertGenresWithRelation($bookId, $genres);

            $this->insertAuthorsWithRelation($bookId, $authors);

            $this->database->commitTransaction();

            return true;
        } catch (\RuntimeException $e) {
            $this->database->rollBack();
            return $e->getMessage();
        }
    }

    public function deleteBook(int $bookId): bool
    {
        $sql = DeleteQueryBuilder::table("\"Book\"")
          ->where('book_id', '=', 'book_id')
          ->getQuery();
        $stmt = $this->database->execute($sql, [':book_id' => $bookId]);

        return $stmt !== false && $stmt->rowCount() > 0;
    }

    public function isBookExists(int $book_id): bool
    {
        $sql = SelectQueryBuilder::table("\"Book\"")
          ->select('*')
          ->where('book_id', '=', 'book_id')
          ->limit(1)
          ->getQuery();

        $book = $this->database->execute($sql, [':book_id' => $book_id]);

        return $book->rowCount() > 0;
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
     * Firstly, method determines whether some tables have data that already
     *   exists, if so, return an array of all identifiers found by
     *   "selectColumn" field, if not, insert all new values and return an
     *   array of their id's
     *
     * @return array
     */
    protected function findOrCreateEntity(
      string $tableName,
      string $selectColumn,
      string $conditionColumn,
      array $values
    ): array {
        $existingEntities = [];
        $newEntities = [];

        foreach ($values as $value) {
            $findSameEntitySQL = SelectQueryBuilder::table($tableName)
              ->select($selectColumn)
              ->where($conditionColumn, '=', 'value')
              ->getQuery();

            $stmt = $this->database->execute($findSameEntitySQL,
              [':value' => $value]);
            $existingEntity = $stmt->fetch();

            if ($existingEntity) {
                $existingEntities[$value] = $existingEntity[$selectColumn];
            } else {
                $newEntities[] = $value;
            }
        }
        if ($newEntities) {
            $saveEntitySQL = InsertQueryBuilder::table($tableName)
              ->values([$conditionColumn])->getQuery();

            foreach ($newEntities as $newValue) {
                $stmt = $this->database->execute($saveEntitySQL ,
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
        $genresId = $this->findOrCreateEntity("\"Genre\"", 'genre_id', 'genre_name',
          $genres);
        foreach ($genresId as $genreId) {
            $this->insertRelation("\"Book_Genre\"",
              ['book_id' => $bookId, 'genre_id' => $genreId]);
        }
    }

    private function insertAuthorsWithRelation(int $bookId, array $authors): void
    {
        $authorsId = $this->findOrCreateEntity("\"Author\"", 'author_id', 'name',
          $authors);
        foreach ($authorsId as $authorId) {
            $this->insertRelation("\"Book_Author\"",
              ['book_id' => $bookId, 'author_id' => $authorId]);
        }
    }
    private function getBookById(SelectQueryBuilder $sql, mixed $book_id, int $limit, int $offset): array
    {
        $sql->where('B.book_id', '=', 'book_id')
          ->groupBy('B.book_id', 'B.title', 'B.year', 'B.isbn')
          ->limit($offset, $limit);
        return $this->database->execute($sql->getQuery(), [':book_id' => $book_id])->fetchAll();
    }

    private function getAllBooks(SelectQueryBuilder $sql, int $limit, int $offset): array
    {
        $sql->groupBy('B.book_id', 'B.title', 'B.year', 'B.isbn')
          ->limit($offset, $limit);
        return $this->database->execute($sql->getQuery())->fetchAll();
    }

    private function getAllFavoriteBooks(SelectQueryBuilder $sql, mixed $user_id, int $limit, int $offset): array
    {
        $sql->join("\"User_Book\"", 'book_id', '=', 'B.book_id')
          ->where('user_id', '=', 'user_id')
          ->groupBy('B.book_id', 'B.title', 'B.year', 'B.isbn')
          ->limit($offset, $limit);

        return $this->database->execute($sql->getQuery(), [':user_id' => $user_id])->fetchAll();
    }

    private function getOneFavoriteBookById(SelectQueryBuilder $sql, mixed $user_id, mixed $book_id ): array
    {
        $sql->join("\"User_Book\"", 'book_id', '=', 'B.book_id')
          ->where("\"User_Book\".user_id", '=', 'user_id')
          ->where('B.book_id', '=', 'book_id')
          ->groupBy('B.book_id', 'B.title', 'B.year', 'B.isbn');


        return $this->database->execute($sql->getQuery(), [':user_id' => $user_id, ':book_id' => $book_id])->fetchAll();
    }
}