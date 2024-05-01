PRAGMA FOREIGN_KEYS = ON;

DROP TABLE IF EXISTS User;

CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE
    password TEXT NOT NULL,
    address TEXT NOT NULL,
    profile_picture BLOB
);

DROP TABLE IF EXISTS Admin;

CREATE TABLE Admin (
    id INTEGER PRIMARY KEY,
    user_id INTEGER NOT NULL UNIQUE REFERENCES User(id)
);

DROP TABLE IF EXISTS Category;

CREATE TABLE Category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    id INTEGER REFERENCES Subcategory(id),
);

DROP TABLE IF EXISTS Subcategory;

CREATE TABLE Subcategory (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE,
  category_id INTEGER NOT NULL REFERENCES Category(id)
);

DROP TABLE IF EXISTS Item;

CREATE TABLE Item (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  seller_id INTEGER NOT NULL REFERENCES User(id),
  title TEXT NOT NULL,
  description TEXT NOT NULL,
  category_id INTEGER NOT NULL REFERENCES Category(id),
  subcategory_id INTEGER NOT NULL REFERENCES SubCategory(id),
  brand TEXT NOT NULL,
  size TEXT NOT NULL,
  color TEXT NOT NULL,
  condition TEXT NOT NULL,
  price DECIMAL(10, 2),
  image_url VARCHAR(255),
);

DROP TABLE IF EXISTS Transaction;

CREATE TABLE Transaction (
  transaction_id INTEGER PRIMARY KEY AUTO_INCREMENT,
  buyer_id INTEGER NOT NULL,
  seller_id INTEGER NOT NULL,
  item_id INTEGER NOT NULL,
  transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price DECIMAL(10, 2),
  FOREIGN KEY (buyer_id) REFERENCES User(id),
  FOREIGN KEY (seller_id) REFERENCES User(id),
  FOREIGN KEY (item_id) REFERENCES Item(id)
);

DROP TABLE IF EXISTS Favourite;

CREATE TABLE Favourite (
  favorite_id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  item_id INT,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (item_id) REFERENCES Items(id)
);

DROP TABLE IF EXISTS Review;

CREATE TABLE Review (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  seller_id INTEGER NOT NULL
  reviewer_id INTEGER NOT NULL,
  item_id INTEGER NOT NULL,
  rating DECIMAL(2,1),
  comment TEXT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (seller_id) REFERENCES Users(id),
  FOREIGN KEY (reviewer_id) REFERENCES Users(id),
  FOREIGN KEY (item_id) REFERENCES Items(id)
);

DROP TABLE IF EXISTS Cart;

CREATE TABLE Cart (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL
  item_id INTEGER NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (item_id) REFERENCES Items(id)
);


INSERT INTO User VALUES (1, 'Dominic', 'Woods', 'dominic111','dominic055@hotmail.com', 'dominic22', 'address', 'profile picture url');
INSERT INTO User VALUES (2, 'Zachary', 'Young', 'zacharyy', 'zacharyyoung@gmail.com', '7110eda4d09e062', 'address', 'profile picture url');
INSERT INTO User VALUES (3, 'Alicia', 'Hamilton', 'alicia', 'aliciahamilton@gmail.com', '711222', 'address', 'profile picture url');
INSERT INTO User VALUES (4, 'Pedro', 'Silva', 'pedro221', 'silvapedro1@hotmail.com', '2c0220',  'address', 'profile picture url');
INSERT INTO User VALUES (5, 'Maria', 'Vieira', 'maria_v', 'mariav@hotmail.com', 'maria111',  'address', 'profile picture url');
INSERT INTO User VALUES (6, 'Luís', 'Marques', 'marquesluis', 'marquesl@hotmail.com', 'ml2001',  'address', 'profile picture url');
INSERT INTO User VALUES (7, 'Pedro', 'Silva', 'pedro221', 'silvapedro1@hotmail.com', 'pedropedro',  'address', 'profile picture url');
INSERT INTO User VALUES (8, 'Márcia', 'Correia', 'marcialuisac', 'marciac@hotmail.com', 'marciaa',  'address', 'profile picture url');
INSERT INTO User VALUES (9, 'João', 'Cruz', 'jcruzj', 'jcruzz@hotmail.com', 'cruz222',  'address', 'profile picture url');
INSERT INTO User VALUES (10, 'Lara', 'Almeida', 'laraalmeida9', 'laralmeida@hotmail.com', '2018EDU',  'address', 'profile picture url');
INSERT INTO User VALUES (11, 'Admin', 'Admin', 'admin1', 'admin1@hotmail.com', 'adminp',  'address', 'profile picture url');


INSERT INTO Admin VALUES(1,11);

INSERT INTO Category VALUES(1, 'WOMEN');
INSERT INTO Category VALUES(2, 'MEN');
INSERT INTO Category VALUES(3, 'KIDS');
INSERT INTO Category VALUES(4, 'JEWELRY');
INSERT INTO Category VALUES(5, 'BAGS');
INSERT INTO Category VALUES(6, 'ACCESSORIES');

INSERT INTO Subcategory VALUES(1,'Dresses', 1); --
INSERT INTO Subcategory VALUES(2,'Tops', 1); --
INSERT INTO Subcategory VALUES(3,'Jeans', 1); --
INSERT INTO Subcategory VALUES(4,'Skirts', 1); --
INSERT INTO Subcategory VALUES(37,'Shorts', 1); --
INSERT INTO Subcategory VALUES(5,'Pants', 1); --
INSERT INTO Subcategory VALUES(6,'Swimwear', 1); --
INSERT INTO Subcategory VALUES(7,'Coats', 1); --
INSERT INTO Subcategory VALUES(8,'Shoes', 1); --

INSERT INTO Subcategory VALUES(9,'Shirts', 2); --
INSERT INTO Subcategory VALUES(10,'Jeans', 2); --
INSERT INTO Subcategory VALUES(11,'Pants', 2); --
INSERT INTO Subcategory VALUES(38,'Shorts', 2); --
INSERT INTO Subcategory VALUES(12,'Swimwear', 2); --
INSERT INTO Subcategory VALUES(13,'Coats', 2); --
INSERT INTO Subcategory VALUES(14,'Shoes', 2); --

INSERT INTO Subcategory VALUES(15,'Dresses', 3); -- 
INSERT INTO Subcategory VALUES(16,'Tops', 3); --
INSERT INTO Subcategory VALUES(17,'Jeans', 3);
INSERT INTO Subcategory VALUES(18,'Skirts', 3);
INSERT INTO Subcategory VALUES(39,'Shorts', 3);
INSERT INTO Subcategory VALUES(19,'Pants', 3);
INSERT INTO Subcategory VALUES(20,'Swimwear', 3);
INSERT INTO Subcategory VALUES(21,'Coats', 3); --
INSERT INTO Subcategory VALUES(22,'Shoes', 3);

INSERT INTO Subcategory VALUES(23,'Rings', 4); --
INSERT INTO Subcategory VALUES(24,'Necklaces', 4); -- 
INSERT INTO Subcategory VALUES(25'Earrings', 4); -- 
INSERT INTO Subcategory VALUES(26,'Bracelets', 4); --

INSERT INTO Subcategory VALUES(27,'Shoulder Bags', 5); --
INSERT INTO Subcategory VALUES(28,'Handbags', 5); -- 
INSERT INTO Subcategory VALUES(29,'Crossbody Bags', 5); --
INSERT INTO Subcategory VALUES(30,'Clutch Bags', 5); --

INSERT INTO Subcategory VALUES(31,'Belts', 6); -- 
INSERT INTO Subcategory VALUES(32,'Sunglasses', 6); -- 
INSERT INTO Subcategory VALUES(33,'Hats', 6); --
INSERT INTO Subcategory VALUES(34,'Scarfs', 6); -- 
INSERT INTO Subcategory VALUES(35,'Wallets', 6); -- 
INSERT INTO Subcategory VALUES(36,'Watches', 6); -- 

--Women Items
INSERT INTO Item VALUES (1, 3,
  'Gucci Coat',
  'beige coat',
  1,7,
  'Gucci', 'L', 'Beige',
  'Excelent', 400.00,
  'image');
INSERT INTO Item VALUES (2, 5,
  'Jimmy Choo Heels',
  'pink shoes',
  1,8,
  'Jimmy Choo', 'M', 'Pink',
  'Very good', 450.00,
  'image');
INSERT INTO Item VALUES (3, 2,
  'Balenciaga Jeans',
  'beautiful jeans',
  1, 3,
  'Balenciaga', 'XS', 'Blue',
  'Very good', 910.00,
  'image');
INSERT INTO Item VALUES (4, 7,
  'Dior skirt',
  'beautiful skirt',
  1, 4,
  'Dior', 'L', 'Blue',
  'Very good', 725.00,
  'image');
INSERT INTO Item VALUES (5, 2,
  'Burberry Dress',
  'very good quality',
  1,1,
  'Burberry', 'M', 'Brown',
  'Excelent', 545.00,
  'image');
INSERT INTO Item VALUES (19, 9,
  'Miu Miu Top',
  'Navy blue top',
  1,2,
  'Burberry', 'M', 'Blue',
  'Bad', 100.00,
  'image');
  INSERT INTO Item VALUES (20, 6,
  'Fendi Trousers',
  'Black trousers',
  1,5,
  'Fendi', 'L', 'Black',
  'Excellent', 700.00,
  'image');
  INSERT INTO Item VALUES (21, 2,
  'Dior Swimsuit',
  'Navy blue swimsuit',
  1,6,
  'Dior', 'M', 'Blue',
  'Bad', 100.00,
  'image');
  INSERT INTO Item VALUES (22, 2,
  'Dolce&Gabbana Shorts',
  'Brown Shorts',
  1,37,
  'Dolce&Gabbana', 'L', 'Brown',
  'Bad', 90.00,
  'image');
--Men Items
INSERT INTO Item VALUES (6, 10,
  'Prada Jacket',
  'green jacket',
  2, 13,
  'Prada', 'S', 'Green',
  'Very Good', 550.00,
  'image');
INSERT INTO Item VALUES (7, 2,
  'Louis Vuitton Sneakers',
  'white sneakers',
  2, 14, 
  'Louis Vuitton', 'XL', 'White',
  'Good', 500.00,
  'image');
INSERT INTO Item VALUES (8, 5,
  'Gucci Shirt',
  'white shirt',
  2, 9,
  'Gucci', 'M', 'White',
  'Bad', 150.00,
  'image');
INSERT INTO Item VALUES (23, 1,
  'Gucci Jeans',
  'Light jeans',
  2, 10,
  'Gucci', 'XL', 'Blue',
  'Very good', 300.00,
  'image');
INSERT INTO Item VALUES (24, 4,
  'Classic Bulgari Trousers',
  'Black Trousers',
  2, 11,
  'Bulgari', 'XS', 'Black',
  'Very good', 415.00,
  'image');
INSERT INTO Item VALUES (25, 6,
  'Armani Shorts',
  'Beige shorts',
  2, 38,
  'Armani', 'S', 'Beige',
  'Good', 95.00,
  'image');
INSERT INTO Item VALUES (26, 8,
  'Prada Swim Shorts',
  'Red shorts',
  2, 12,
  'Armani', 'S', 'Red',
  'Good', 95.00,
  'image');
--Kids Items
INSERT INTO Item VALUES (9, 3,
  'Blouse Miu Miu',
  'black top',
  3, 16,
  'Miu Miu', 'S', 'Black',
  'Good', 510.00,
  'image');
INSERT INTO Item VALUES (10, 8,
  'Prada Jacket',
  'pink jacket',
  3, 21,
  'Prada', 'S', 'Pink',
  'Good', 600.00,
  'image');
INSERT INTO Item VALUES (27, 8,
  'Gucci dress',
  'pink dress',
  3, 15,
  'Gucci', 'L', 'Pink',
  'Very good', 700.00,
  'image');
--Jewerly Items
INSERT INTO Item VALUES (11, 7,
  'Swarovski Necklace',
  'diamond necklace',
  4, 24,
  'Swarovski', 'S', 'Silver',
  'Very Good', 2500.00,
  'image');
INSERT INTO Item VALUES (12, 6,
  'Cartier Ring',
  'gold ring',
  4, 23,
  'Cartier', '17', 'Gold',
  'Good', 1000.00,
  'image');
INSERT INTO Item VALUES (28, 3,
  'Tiffany&Co Earrings',
  'gold earrings',
  4, 25,
  'Tiffany&Co', '-', 'Gold',
  'Good', 900.00,
  'image');
INSERT INTO Item VALUES (29, 6,
  'Van Cleef Bracelet',
  'gold gorgeous bracelet',
  4, 26,
  'Van Cleef', '-', 'Gold',
  'Excellent', 2300.00,
  'image');
INSERT INTO Item VALUES (30, 9,
  'Cartier Bracelet',
  'silver bracelet',
  4, 26,
  'Van Cleef', '-', 'Silver',
  'Bad', 900.00,
  'image');
--Bags 
INSERT INTO Item VALUES (13, 1,
  'Prada Purse',
  'black purse',
  5, 27,
  'Prada', 'S', 'Black',
  'Excelent', 870.00,
  'image');
INSERT INTO Item VALUES (14, 5,
  'Hermes Birkin Bag',
  'Birkin bag',
  5, 28,
  'Hermes', 'Medium', 'Orange',
  'Good', 10000.00,
  'image');
INSERT INTO Item VALUES (15, 9,
  'Chanel Purse',
  'pink purse',
  5, 29,
  'Chanel', 'Large', 'Pink',
  'Very good', 800.00,
  'image');
INSERT INTO Item VALUES (31, 9,
  'Chloé Clutch',
  'Gold purse',
  5, 30,
  'Chanel', 'Small', 'Gold',
  'Bad', 500.00,
  'image');
--Accessories
INSERT INTO Item VALUES (16, 7,
  'Rolex Watch',
  'stylish rolex watch',
  6, 36,
  'Rolex', '6', 'Silver',
  'Very Good', 25000.00,
  'image');
INSERT INTO Item VALUES (17, 3,
  'YSL Sunglasses',
  'beautiful sunglasses',
  6, 32,
  'Yves Saint Laurent', 'S', 'Black',
  'Good', 300.00,
  'image');
INSERT INTO Item VALUES (32, 3,
  'Polo Ralph Lauren hat',
  'Purple hat',
  6, 33,
  'Polo Ralph Lauren', 'S', 'Purple',
  'Good', 150.00,
  'image');
INSERT INTO Item VALUES (18, 2,
  'Burberry Scarf',
  'very good quality scarf',
  6,34,
  'Burberry', 'M', 'Brown',
  'Excelent', 260.00,
  'image');
INSERT INTO Item VALUES (33, 10,
  'Louis Vuitton wallet',
  'Red small wallet',
  6,35,
  'Louis Vuitton', 'Small', 'Red',
  'Very good', 110.00,
  'image');
INSERT INTO Item VALUES (34, 10,
  'Jacquemus belt',
  'Black simple belt',
  6,31,
  'Jacquemus', '-', 'Black',
  'Good', 120.00,
  'image');


  

INSERT INTO Transaction VALUES (1, 1, 3, 2, "2022-12-20 10:30:00", 450.00);
INSERT INTO Transaction VALUES (2, 4, 10, 16, "2023-03-02 10:30:00", 24000.00);
INSERT INTO Transaction VALUES (3, 10, 7, 11, "2023-01-17 10:30:00", 2500.00);
INSERT INTO Transaction VALUES (4, 5, 1, 4, "2023-04-14 10:30:00", 725.00);
INSERT INTO Transaction VALUES (5, 4, 7, 7, "2023-02-21 10:30:00", 500.00);

INSERT INTO Favourite VALUES (1, 3, 15);
INSERT INTO Favourite VALUES (2, 3, 13);
INSERT INTO Favourite VALUES (3, 3, 11);
INSERT INTO Favourite VALUES (4, 3, 8);
INSERT INTO Favourite VALUES (5, 7, 1);
INSERT INTO Favourite VALUES (6, 10, 3);
INSERT INTO Favourite VALUES (7, 3, 5);
INSERT INTO Favourite VALUES (8, 5, 7);
INSERT INTO Favourite VALUES (9, 5, 8);
INSERT INTO Favourite VALUES (10, 6, 7);
INSERT INTO Favourite VALUES (11, 9, 7);
INSERT INTO Favourite VALUES (12, 8, 7);

INSERT INTO Review VALUES (1, 3, 1, 2, 4.5, "Very professional.");
INSERT INTO Review VALUES (1, 10, 4, 16, 5.0, "Excellent");
INSERT INTO Review VALUES (1, 7, 10, 11, 3.5, "");
INSERT INTO Review VALUES (1, 1, 5, 4, 5.0, "Nice!");
INSERT INTO Review VALUES (1, 7, 4, 7, 5.0, "");