CREATE TABLE IF NOT EXISTS estudiantes (
    id     SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad   INT NOT NULL
);

INSERT INTO estudiantes (nombre, edad) VALUES
    ('Ana Garcia',    20),
    ('Juan Perez',    22),
    ('Maria Lopez',   19),
    ('Carlos Ruiz',   21),
    ('Sofia Herrera', 23);