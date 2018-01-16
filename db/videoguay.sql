------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS socios CASCADE;

CREATE TABLE socios
(
    id        bigserial    PRIMARY KEY
  , numero    numeric(6)   NOT NULL UNIQUE
  , nombre    varchar(255) NOT NULL
  , direccion varchar(255)
  , telefono  numeric(9)   CONSTRAINT ck_telefono_no_negativo
                           CHECK (telefono >= 0)
);

CREATE INDEX idx_socios_nombre ON socios (nombre);
CREATE INDEX idx_socios_telefono ON socios (telefono);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(
    id         bigserial    PRIMARY KEY
  , codigo     numeric(4)   NOT NULL UNIQUE
  , titulo     varchar(255) NOT NULL
  , precio_alq numeric(5,2) NOT NULL
);

CREATE INDEX idx_peliculas_titulo ON peliculas (titulo);

DROP TABLE IF EXISTS alquileres CASCADE;

CREATE TABLE alquileres
(
    id          bigserial    PRIMARY KEY
  , socio_id    bigint       NOT NULL REFERENCES socios (id)
                             ON DELETE NO ACTION ON UPDATE CASCADE
  , pelicula_id bigint       NOT NULL REFERENCES peliculas (id)
                             ON DELETE NO ACTION ON UPDATE CASCADE
  , created_at  timestamp(0) NOT NULL DEFAULT current_timestamp
  , devolucion  timestamp(0)
  , UNIQUE (socio_id, pelicula_id, created_at)
);

CREATE INDEX idx_alquileres_pelicula_id ON alquileres (pelicula_id);
CREATE INDEX idx_alquileres_created_at ON alquileres (created_at DESC);
