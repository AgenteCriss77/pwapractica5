CREATE DATABASE biblioteca_online;
USE biblioteca_online;

-- Primero crear la tabla roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL
);

-- Luego insertar los roles
INSERT INTO roles (name) VALUES ('Administrator'), ('Librarian'), ('Reader');

-- Después crear la tabla users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(100) NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Finalmente insertar el usuario administrador
INSERT INTO users (username, email, password, role_id) 
VALUES ('admin', 'admin@example.com', 'admin123', 1);


-- Crear la tabla books
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    genre VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 1
);

-- Crear la tabla transactions
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    date_of_issue DATE NOT NULL,
    return_date DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'prestado',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
); 