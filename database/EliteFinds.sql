CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE
    profile_picture BLOB
);

CREATE TABLE Admin (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE REFERENCES User(id)
);

CREATE TABLE Category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE Items (
  item_id INT PRIMARY KEY AUTO_INCREMENT,
  seller_id INT,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  category VARCHAR(50),
  brand VARCHAR(50),
  size VARCHAR(20),
  color VARCHAR(50),
  condition VARCHAR(50),
  price DECIMAL(10, 2),
  image_url VARCHAR(255),
  FOREIGN KEY (seller_id) REFERENCES Users(user_id)
);

CREATE TABLE transactions (
  transaction_id INT PRIMARY KEY AUTO_INCREMENT,
  buyer_id INT,
  seller_id INT,
  item_id INT,
  transaction_date DATETIME,
  amount_paid DECIMAL(10, 2),
  FOREIGN KEY (buyer_id) REFERENCES Users(user_id),
  FOREIGN KEY (seller_id) REFERENCES Users(user_id),
  FOREIGN KEY (item_id) REFERENCES Items(item_id)
);

CREATE TABLE favourites (
  favorite_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  item_id INT,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (item_id) REFERENCES Items(item_id)
);

CREATE TABLE reviews (
  review_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  item_id INT,
  rating INT,
  comment TEXT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(user_id),
  FOREIGN KEY (item_id) REFERENCES Items(item_id)
);


-- All passwords are 1234 in SHA-1 format
INSERT INTO users VALUES (1, 'dominic', 'dominic055@hotmail.com', 'dominic111', 'Dominic', 'Woods', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES (2, 'zachary', 'zacharyyoung@gmail.com', '7110eda4d09e062', 'Zachary', 'Young', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES (3, 'alicia',  'aliciahamilton@gmail.com', '711222', 'Alicia', 'Hamilton', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES (4, 'pedro221', 'silvapedro', 'silvapedro1@hotmail.com', '2c0220', 'Pedro', 'Silva', 'phone number', 'address', 'profile picture url');

INSERT INTO items VALUES (1, 3
  'Gucci Coat',
  'beige coat',
  'Outerwear',
  'Gucci', 'L', 'Beige',
  'Excelent', 400,
  'image')
 
INSERT INTO items VALUES (2, 2
  'Jimmy Choo Shoes',
  'pink shoes',
  'Shoes',
  'Jimmy Choo', 'M', 'Pink',
  'Very good', 450,
  'image')
 
INSERT INTO items VALUES (3, 1
  'Prada Purse',
  'black purse',
  'Bags',
  'Prada', 'S', 'Black',
  'Excelent', 870,
  'image')
  
INSERT INTO items VALUES (4, 3
  'Blusa Miu Miu',
  'black top',
  'Tops',
  'Miu Miu', 'S', 'Black',
  'Good', 510,
  'image')
  

INSERT INTO transactions VALUES (2, 1, 3, 2, '20/04/2024', 450.00)
INSERT INTO transactions VALUES (1, 4, 3, 4, '22/04/2024', 510.00)

