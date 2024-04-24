CREATE TABLE users (
  user_id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL PRIMARY KEY,
  email VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  phone_number VARCHAR(20), -- OPCIONAL
  address VARCHAR(255), --OPCIONAL
  profile_picture_url VARCHAR(255)
);

CREATE TABLE items (
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

CREATE TABLE favorites (
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

CREATE TABLE comments (
  id INTEGER PRIMARY KEY,            -- comment id
  news_id INTEGER REFERENCES news,   -- news item this comment is about
  username VARCHAR REFERENCES users, -- user that wrote the comment
  published INTEGER,                 -- date when news item was published in epoch format
  text VARCHAR                       -- comment text
);

-- All passwords are 1234 in SHA-1 format
INSERT INTO users VALUES ('1', 'dominic', 'dominic055@hotmail.com', 'dominic111', 'Dominic', 'Woods', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES ('2', 'zachary', 'zacharyyoung@gmail.com', '7110eda4d09e062', 'Zachary', 'Young', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES ('3', 'alicia',  'aliciahamilton@gmail.com', '711222', 'Alicia', 'Hamilton', 'phone number', 'address', 'profile picture url');
INSERT INTO users VALUES ('4', 'pedro221', 'silvapedro', 'silvapedro1@hotmail.com', '2c0220', 'Pedro', 'Silva', 'phone number', 'address', 'profile picture url');

INSERT INTO items VALUES ('1', '3'
  'Gucci Coat',
  'beige coat',
  'Outerwear',
  'Gucci', 'L', 'Beige',
  'Excelent', '400',
  'image')
 
INSERT INTO items VALUES ('2', '2'
  'Jimmy Choo Shoes',
  'pink shoes',
  'Shoes',
  'Jimmy Choo', 'M', 'Pink',
  'Very good', '450',
  'image')
 
INSERT INTO items VALUES ('3', '1'
  'Prada Purse',
  'black purse',
  'Bags',
  'Prada', 'S', 'Black',
  'Excelent', '870',
  'image')
  
INSERT INTO items VALUES ('4', '3'
  'Blusa Miu Miu',
  'black top',
  'Tops',
  'Miu Miu', 'S', 'Black',
  'Good', '510',
  'image')
  

INSERT INTO transactions VALUES ('2', '1', '3', '2', '20/04/2024', '450.00')
INSERT INTO transactions VALUES ('1', '4', '3', '4', '22/04/2024', '510.00')


INSERT INTO comments VALUES (NULL,
  4,
  'dominic',
  1508247532,
  'Aliquam maximus commodo dui, ut viverra urna vulputate et. Donec posuere vitae sem sed vehicula. Sed in erat eu diam fringilla sodales. Aenean lacinia vulputate nisl, dignissim dignissim nisl. Nam at nibh mollis, facilisis nibh sit amet, mattis urna. Maecenas.'
);

INSERT INTO comments VALUES (NULL,
  4,
  'abril',
  1508247632,
  'Duis scelerisque purus fermentum turpis euismod congue. Phasellus sit amet sem mollis, imperdiet quam porta, volutpat purus. In et sodales urna, sed cursus lectus. Vivamus a massa vitae nisl lobortis laoreet nec tristique magna. Mauris egestas ipsum eu sem lacinia.'
);

INSERT INTO comments VALUES (NULL,
  3,
  'alicia',
  1508247132,
  'Phasellus at neque nec nunc scelerisque eleifend eu eu risus. Praesent in nibh viverra, posuere ligula condimentum, accumsan tellus. Vivamus varius sem a mauris finibus, ac iaculis risus scelerisque. Nullam fermentum leo dui, at fermentum tellus consequat id. Pellentesque eleifend.'
);
