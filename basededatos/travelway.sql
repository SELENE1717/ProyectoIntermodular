
-- Borrar tablas en orden de dependencias inverso
DROP TABLE IF EXISTS SE_ASIGNA;
DROP TABLE IF EXISTS SE_SUSCRIBE;
DROP TABLE IF EXISTS PASAPORTE;
DROP TABLE IF EXISTS GUIA;
DROP TABLE IF EXISTS DESTINO;
DROP TABLE IF EXISTS USUARIOS;

-- Crear tabla USUARIOS
CREATE TABLE USUARIOS (
  id_usuario SERIAL PRIMARY KEY,
  nombre VARCHAR(50),
  apellidos VARCHAR(100),
  edad INT,
  email VARCHAR(100) UNIQUE, -- LE HE PUESTO UNIQUE PORQUE EN EL LOGIN LO COLOCAN PARA IDENTIFICARSE
  password VARCHAR(100)
);

-- Crear tabla PASAPORTE
CREATE TABLE PASAPORTE (
  id_pasaporte SERIAL PRIMARY KEY,
  numero VARCHAR(20),
  fecha_expedicion DATE,
  caducidad DATE,
  id_usuario INT UNIQUE,
  FOREIGN KEY (id_usuario)
    REFERENCES USUARIOS(id_usuario)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Crear tabla DESTINO
CREATE TABLE DESTINO (
  id_destino SERIAL PRIMARY KEY,
  ciudad VARCHAR(100),
  pais VARCHAR(100),
  requiere_pasaporte BOOLEAN
);

-- Crear tabla GUIA
CREATE TABLE GUIA (
  id_guia SERIAL PRIMARY KEY,
  nombre VARCHAR(50),
  apellidos VARCHAR(100),
  especialidad VARCHAR(100)
);

-- Crear tabla SE_ASIGNA
CREATE TABLE SE_ASIGNA (
  id_asignacion SERIAL PRIMARY KEY,
  id_destino INT UNIQUE,
  id_guia INT UNIQUE,
  FOREIGN KEY (id_destino) REFERENCES DESTINO(id_destino),
  FOREIGN KEY (id_guia) REFERENCES GUIA(id_guia)
);

-- Crear tabla SE_SUSCRIBE
CREATE TABLE SE_SUSCRIBE (
  id_reserva SERIAL PRIMARY KEY,
  id_usuario INT,
  id_destino INT,
  FOREIGN KEY (id_usuario) REFERENCES USUARIOS(id_usuario),
  FOREIGN KEY (id_destino) REFERENCES DESTINO(id_destino)
);
