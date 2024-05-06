--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: Author; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Author" (
    author_id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public."Author" OWNER TO postgres;

--
-- Name: Author_author_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Author_author_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Author_author_id_seq" OWNER TO postgres;

--
-- Name: Author_author_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Author_author_id_seq" OWNED BY public."Author".author_id;


--
-- Name: Book; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Book" (
    book_id integer NOT NULL,
    title character varying(255) NOT NULL,
    year integer NOT NULL,
    isbn bigint NOT NULL,
    description text
);


ALTER TABLE public."Book" OWNER TO postgres;

--
-- Name: Book_Author; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Book_Author" (
    id integer NOT NULL,
    book_id integer NOT NULL,
    author_id integer NOT NULL
);


ALTER TABLE public."Book_Author" OWNER TO postgres;

--
-- Name: Book_Author_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Book_Author_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Book_Author_id_seq" OWNER TO postgres;

--
-- Name: Book_Author_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Book_Author_id_seq" OWNED BY public."Book_Author".id;


--
-- Name: Book_Genre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Book_Genre" (
    id integer NOT NULL,
    book_id integer NOT NULL,
    genre_id integer NOT NULL
);


ALTER TABLE public."Book_Genre" OWNER TO postgres;

--
-- Name: Book_Genre_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Book_Genre_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Book_Genre_id_seq" OWNER TO postgres;

--
-- Name: Book_Genre_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Book_Genre_id_seq" OWNED BY public."Book_Genre".id;


--
-- Name: Book_book_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Book_book_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Book_book_id_seq" OWNER TO postgres;

--
-- Name: Book_book_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Book_book_id_seq" OWNED BY public."Book".book_id;


--
-- Name: Genre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Genre" (
    genre_id integer NOT NULL,
    genre_name character varying(255)
);


ALTER TABLE public."Genre" OWNER TO postgres;

--
-- Name: Genre_genre_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Genre_genre_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Genre_genre_id_seq" OWNER TO postgres;

--
-- Name: Genre_genre_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Genre_genre_id_seq" OWNED BY public."Genre".genre_id;


--
-- Name: Refresh_user_token; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Refresh_user_token" (
    id integer NOT NULL,
    user_id integer NOT NULL,
    refresh_token character varying(255) NOT NULL,
    expires_in bigint NOT NULL,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public."Refresh_user_token" OWNER TO postgres;

--
-- Name: Refresh_user_token_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Refresh_user_token_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."Refresh_user_token_id_seq" OWNER TO postgres;

--
-- Name: Refresh_user_token_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Refresh_user_token_id_seq" OWNED BY public."Refresh_user_token".id;


--
-- Name: User; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."User" (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    email character varying(255),
    password character varying(255) NOT NULL,
    "createdAt" timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    is_admin boolean DEFAULT false
);


ALTER TABLE public."User" OWNER TO postgres;

--
-- Name: User_Book; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."User_Book" (
    id integer NOT NULL,
    user_id integer NOT NULL,
    book_id integer NOT NULL
);


ALTER TABLE public."User_Book" OWNER TO postgres;

--
-- Name: User_Book_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."User_Book_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."User_Book_id_seq" OWNER TO postgres;

--
-- Name: User_Book_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."User_Book_id_seq" OWNED BY public."User_Book".id;


--
-- Name: User_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."User_id_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."User_id_seq" OWNER TO postgres;

--
-- Name: User_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."User_id_seq" OWNED BY public."User".id;


--
-- Name: Author author_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Author" ALTER COLUMN author_id SET DEFAULT nextval('public."Author_author_id_seq"'::regclass);


--
-- Name: Book book_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book" ALTER COLUMN book_id SET DEFAULT nextval('public."Book_book_id_seq"'::regclass);


--
-- Name: Book_Author id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Author" ALTER COLUMN id SET DEFAULT nextval('public."Book_Author_id_seq"'::regclass);


--
-- Name: Book_Genre id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Genre" ALTER COLUMN id SET DEFAULT nextval('public."Book_Genre_id_seq"'::regclass);


--
-- Name: Genre genre_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Genre" ALTER COLUMN genre_id SET DEFAULT nextval('public."Genre_genre_id_seq"'::regclass);


--
-- Name: Refresh_user_token id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Refresh_user_token" ALTER COLUMN id SET DEFAULT nextval('public."Refresh_user_token_id_seq"'::regclass);


--
-- Name: User id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User" ALTER COLUMN id SET DEFAULT nextval('public."User_id_seq"'::regclass);


--
-- Name: User_Book id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User_Book" ALTER COLUMN id SET DEFAULT nextval('public."User_Book_id_seq"'::regclass);


--
-- Data for Name: Author; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Author" (author_id, name) FROM stdin;
\.


--
-- Data for Name: Book; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Book" (book_id, title, year, isbn, description) FROM stdin;
\.


--
-- Data for Name: Book_Author; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Book_Author" (id, book_id, author_id) FROM stdin;
\.


--
-- Data for Name: Book_Genre; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Book_Genre" (id, book_id, genre_id) FROM stdin;
\.


--
-- Data for Name: Genre; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Genre" (genre_id, genre_name) FROM stdin;
\.


--
-- Data for Name: Refresh_user_token; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Refresh_user_token" (id, user_id, refresh_token, expires_in, created_at) FROM stdin;
\.


--
-- Data for Name: User; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."User" (id, name, email, password, "createdAt", is_admin) FROM stdin;
\.


--
-- Data for Name: User_Book; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."User_Book" (id, user_id, book_id) FROM stdin;
\.


--
-- Name: Author_author_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Author_author_id_seq"', 1, false);


--
-- Name: Book_Author_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Book_Author_id_seq"', 1, false);


--
-- Name: Book_Genre_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Book_Genre_id_seq"', 1, false);


--
-- Name: Book_book_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Book_book_id_seq"', 1, false);


--
-- Name: Genre_genre_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Genre_genre_id_seq"', 1, false);


--
-- Name: Refresh_user_token_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Refresh_user_token_id_seq"', 1, false);


--
-- Name: User_Book_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."User_Book_id_seq"', 1, false);


--
-- Name: User_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."User_id_seq"', 1, false);


--
-- Name: Author Author_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Author"
    ADD CONSTRAINT "Author_pkey" PRIMARY KEY (author_id);


--
-- Name: Book_Author Book_Author_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Author"
    ADD CONSTRAINT "Book_Author_pkey" PRIMARY KEY (id);


--
-- Name: Book_Genre Book_Genre_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Genre"
    ADD CONSTRAINT "Book_Genre_pkey" PRIMARY KEY (id);


--
-- Name: Book Book_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book"
    ADD CONSTRAINT "Book_pkey" PRIMARY KEY (book_id);


--
-- Name: Genre Genre_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Genre"
    ADD CONSTRAINT "Genre_pkey" PRIMARY KEY (genre_id);


--
-- Name: Refresh_user_token Refresh_user_token_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Refresh_user_token"
    ADD CONSTRAINT "Refresh_user_token_pkey" PRIMARY KEY (id);


--
-- Name: User_Book User_Book_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User_Book"
    ADD CONSTRAINT "User_Book_pkey" PRIMARY KEY (id);


--
-- Name: User User_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User"
    ADD CONSTRAINT "User_email_key" UNIQUE (email);


--
-- Name: User User_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User"
    ADD CONSTRAINT "User_pkey" PRIMARY KEY (id);


--
-- Name: Book_Author Book_Author_author_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Author"
    ADD CONSTRAINT "Book_Author_author_id_fkey" FOREIGN KEY (author_id) REFERENCES public."Author"(author_id) ON DELETE CASCADE;


--
-- Name: Book_Author Book_Author_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Author"
    ADD CONSTRAINT "Book_Author_book_id_fkey" FOREIGN KEY (book_id) REFERENCES public."Book"(book_id) ON DELETE CASCADE;


--
-- Name: Book_Genre Book_Genre_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Genre"
    ADD CONSTRAINT "Book_Genre_book_id_fkey" FOREIGN KEY (book_id) REFERENCES public."Book"(book_id) ON DELETE CASCADE;


--
-- Name: Book_Genre Book_Genre_genre_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Book_Genre"
    ADD CONSTRAINT "Book_Genre_genre_id_fkey" FOREIGN KEY (genre_id) REFERENCES public."Genre"(genre_id) ON DELETE CASCADE;


--
-- Name: Refresh_user_token Refresh_user_token_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Refresh_user_token"
    ADD CONSTRAINT "Refresh_user_token_user_id_fkey" FOREIGN KEY (user_id) REFERENCES public."User"(id) ON DELETE CASCADE;


--
-- Name: User_Book User_Book_book_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User_Book"
    ADD CONSTRAINT "User_Book_book_id_fkey" FOREIGN KEY (book_id) REFERENCES public."Book"(book_id) ON DELETE CASCADE;


--
-- Name: User_Book User_Book_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."User_Book"
    ADD CONSTRAINT "User_Book_user_id_fkey" FOREIGN KEY (user_id) REFERENCES public."User"(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

