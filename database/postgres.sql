CREATE DATABASE book_api;

-- Table: Author
DROP TABLE IF EXISTS "Author";
CREATE TABLE "Author" (
                          "author_id" SERIAL PRIMARY KEY,
                          "name" VARCHAR(255) NOT NULL
);

-- Table: Book
DROP TABLE IF EXISTS "Book";
CREATE TABLE "Book" (
                        "book_id" SERIAL PRIMARY KEY,
                        "title" VARCHAR(255) NOT NULL,
                        "year" INTEGER NOT NULL,
                        "isbn" BIGINT NOT NULL,
                        "description" TEXT
);

-- Table: Book_Author
DROP TABLE IF EXISTS "Book_Author";
CREATE TABLE "Book_Author" (
                               "id" BIGSERIAL PRIMARY KEY,
                               "book_id" INTEGER NOT NULL,
                               "author_id" INTEGER NOT NULL,
                               FOREIGN KEY ("book_id") REFERENCES "Book" ("book_id") ON DELETE CASCADE,
                               FOREIGN KEY ("author_id") REFERENCES "Author" ("author_id") ON DELETE CASCADE
);

-- Table: Genre
DROP TABLE IF EXISTS "Genre";
CREATE TABLE "Genre" (
                         "genre_id" SERIAL PRIMARY KEY,
                         "genre_name" VARCHAR(255)
);

-- Table: Book_Genre
DROP TABLE IF EXISTS "Book_Genre";
CREATE TABLE "Book_Genre" (
                              "id" BIGSERIAL PRIMARY KEY,
                              "book_id" INTEGER NOT NULL,
                              "genre_id" INTEGER NOT NULL,
                              FOREIGN KEY ("book_id") REFERENCES "Book" ("book_id") ON DELETE CASCADE,
                              FOREIGN KEY ("genre_id") REFERENCES "Genre" ("genre_id") ON DELETE CASCADE
);

-- Table: User
DROP TABLE IF EXISTS "User";
CREATE TABLE "User" (
                        "id" SERIAL PRIMARY KEY,
                        "name" VARCHAR(100) NOT NULL DEFAULT '',
                        "email" VARCHAR(255) UNIQUE,
                        "password" VARCHAR(255) NOT NULL,
                        "createdAt" TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
                        "is_admin" BOOLEAN DEFAULT FALSE
);
-- Table: Refresh_user_token
DROP TABLE IF EXISTS "Refresh_user_token";
CREATE TABLE "Refresh_user_token" (
                                      "id" BIGSERIAL PRIMARY KEY,
                                      "user_id" INTEGER NOT NULL,
                                      "refresh_token" VARCHAR(255) NOT NULL,
                                      "expires_in" BIGINT NOT NULL,
                                      "created_at" TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
                                      FOREIGN KEY ("user_id") REFERENCES "User" ("id") ON DELETE CASCADE
);


-- Table: User_Book
DROP TABLE IF EXISTS "User_Book";
CREATE TABLE "User_Book" (
                             "id" BIGSERIAL PRIMARY KEY,
                             "user_id" INTEGER NOT NULL,
                             "book_id" INTEGER NOT NULL,
                             FOREIGN KEY ("user_id") REFERENCES "User" ("id") ON DELETE CASCADE,
                             FOREIGN KEY ("book_id") REFERENCES "Book" ("book_id") ON DELETE CASCADE
);
