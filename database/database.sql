PRAGMA FOREIGN_KEYS = ON;

DROP TABLE IF EXISTS User;

CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    user_password TEXT NOT NULL,
    user_address TEXT NOT NULL,
    profile_picture BLOB 
);

DROP TABLE IF EXISTS Admin;

CREATE TABLE Admin (
    user_id INTEGER PRIMARY KEY REFERENCES User(id)
);

DROP TABLE IF EXISTS Department;

CREATE TABLE Department (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    d_name TEXT NOT NULL UNIQUE,
);

DROP TABLE IF EXISTS Category;

CREATE TABLE Category (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  c_name TEXT NOT NULL UNIQUE,
  department_id INTEGER NOT NULL REFERENCES Department(id)
);

DROP TABLE IF EXISTS Subcategory;

CREATE TABLE Subcategory (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  subc_name TEXT NOT NULL UNIQUE,
  category_id INTEGER NOT NULL REFERENCES Category(id)
);

DROP TABLE IF EXISTS Item;

CREATE TABLE Item (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  seller_id INTEGER NOT NULL REFERENCES User(id),
  title TEXT NOT NULL,
  item_description TEXT NOT NULL,
  department_id INTEGER NOT NULL REFERENCES Department(id),
  category_id INTEGER NOT NULL REFERENCES Category(id),
  subcategory_id INTEGER NOT NULL REFERENCES Subcategory(id),
  brand TEXT NOT NULL,
  item_size TEXT NOT NULL,
  color TEXT NOT NULL,
  condition TEXT NOT NULL,
  price DECIMAL(10, 2),
  image_url TEXT,
);

DROP TABLE IF EXISTS Transaction;

CREATE TABLE Transaction (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  buyer_id INTEGER NOT NULL REFERENCES User(id),
  seller_id INTEGER NOT NULL REFERENCES User(id),
  item_id INTEGER NOT NULL REFERENCES Item(id),
  transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price DECIMAL(10, 2),
);

DROP TABLE IF EXISTS Favourite;

CREATE TABLE Favourite (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL REFERENCES User(id),
  item_id INTEGER NOT NULL REFERENCES Item(id),
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE
);

DROP TABLE IF EXISTS Review;

CREATE TABLE Review (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  seller_id INTEGER NOT NULL REFERENCES User(id),
  reviewer_id INTEGER NOT NULL REFERENCES User(id),
  item_id INTEGER NOT NULL REFERENCES Item(id),
  rating DECIMAL(2,1) CHECK (rating >= 0 AND rating <= 5),
  comment TEXT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

DROP TABLE IF EXISTS Cart;

CREATE TABLE Cart (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  user_id INTEGER NOT NULL
  item_id INTEGER NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id),
  FOREIGN KEY (item_id) REFERENCES Items(id)
);

DROP TABLE IF EXISTS PendingOrder;

CREATE TABLE PendingOrder (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES User(id),
    item_id INTEGER NOT NULL REFERENCES Item(id),
    quantity INTEGER NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

INSERT INTO Department VALUES(1, 'WOMEN');
INSERT INTO Department VALUES(2, 'MEN');
INSERT INTO Department VALUES(3, 'KIDS');
INSERT INTO Department VALUES(4, 'JEWELRY');
INSERT INTO Department VALUES(5, 'BAGS');
INSERT INTO Department VALUES(6, 'ACCESSORIES');

INSERT INTO Category VALUES(1,'Dresses', 1); --
INSERT INTO Category VALUES(2,'Tops', 1); --
INSERT INTO Category VALUES(3,'Jeans', 1); --
INSERT INTO Category VALUES(4,'Skirts', 1); --
INSERT INTO Category VALUES(37,'Shorts', 1); --
INSERT INTO Category VALUES(5,'Pants', 1); --
INSERT INTO Category VALUES(6,'Swimwear', 1); --
INSERT INTO Category VALUES(7,'Coats', 1); --
INSERT INTO Category VALUES(8,'Shoes', 1); --

INSERT INTO Category VALUES(9,'Shirts', 2); --
INSERT INTO Category VALUES(10,'Jeans', 2); --
INSERT INTO Category VALUES(11,'Pants', 2); --
INSERT INTO Category VALUES(38,'Shorts', 2); --
INSERT INTO Category VALUES(12,'Swimwear', 2); --
INSERT INTO Category VALUES(13,'Coats', 2); --
INSERT INTO Category VALUES(14,'Shoes', 2); --

--INSERT INTO Category VALUES(15,'Dresses', 3); -- 
--INSERT INTO Category VALUES(16,'Tops', 3); --
--INSERT INTO Category VALUES(17,'Jeans', 3);
--INSERT INTO Category VALUES(18,'Skirts', 3);
--INSERT INTO Category VALUES(39,'Shorts', 3);
--INSERT INTO Category VALUES(19,'Pants', 3);
--INSERT INTO Category VALUES(20,'Swimwear', 3);
--INSERT INTO Category VALUES(21,'Coats', 3); --
--INSERT INTO Category VALUES(22,'Shoes', 3);

INSERT INTO Category VALUES(15,'Girl', 3); -- 
INSERT INTO Category VALUES(16,'Boy', 3); --



INSERT INTO Category VALUES(17,'Rings', 4); --
INSERT INTO Category VALUES(18,'Necklaces', 4); -- 
INSERT INTO Category VALUES(19,'Earrings', 4); -- 
INSERT INTO Category VALUES(20,'Bracelets', 4); --

INSERT INTO Category VALUES(21,'Shoulder Bags', 5); --
INSERT INTO Category VALUES(22,'Handbags', 5); -- 
INSERT INTO Category VALUES(23,'Crossbody Bags', 5); --
INSERT INTO Category VALUES(24,'Clutch Bags', 5); --

INSERT INTO Category VALUES(25,'Belts', 6); -- 
INSERT INTO Category VALUES(26,'Sunglasses', 6); -- 
INSERT INTO Category VALUES(27,'Hats', 6); --
INSERT INTO Category VALUES(28,'Scarfs', 6); -- 
INSERT INTO Category VALUES(29,'Wallets', 6); -- 
INSERT INTO Category VALUES(30,'Watches', 6); -- 

-- SUBCATEGORIES!!!!!!!!
--For Woman dresses
INSERT INTO Subcategory VALUES(41,'Mini', 1); 
INSERT INTO Subcategory VALUES(42,'Midi', 1);
INSERT INTO Subcategory VALUES(43,'Maxi', 1); 
--For Woman tops
INSERT INTO Subcategory VALUES(44,'Blouses', 2); 
INSERT INTO Subcategory VALUES(45,'Crop-tops', 2); 
INSERT INTO Subcategory VALUES(46,'Shirts', 2); 
INSERT INTO Subcategory VALUES(47,'T-Shirts', 2); 
--For woman jeans
INSERT INTO Subcategory VALUES(48,'Lose fit', 3); 
INSERT INTO Subcategory VALUES(49,'Skinny Fit', 3); 
INSERT INTO Subcategory VALUES(50,'Bootcut fit', 3); 
-- For woman skirts
INSERT INTO Subcategory VALUES(51,'Mini', 4); 
INSERT INTO Subcategory VALUES(52,'Midi', 4); 
INSERT INTO Subcategory VALUES(53,'Maxi', 4); 
-- For woman shorts
INSERT INTO Subcategory VALUES(54,'short length', 37); 
INSERT INTO Subcategory VALUES(55,'longer length', 37); 
-- For woman pants
INSERT INTO Subcategory VALUES(56,'Lose fit', 5); 
INSERT INTO Subcategory VALUES(57,'Skinny fit', 5); 
INSERT INTO Subcategory VALUES(58,'Bootcut fit', 5); 
-- For woman Swimwear 
INSERT INTO Subcategory VALUES(59,'Bikini top', 6); 
INSERT INTO Subcategory VALUES(60,'Bikini bottom', 6); 
INSERT INTO Subcategory VALUES(61,'Bikini set', 6); 
INSERT INTO Subcategory VALUES(62,'Swimsuits', 6); 
-- For woman coats 
INSERT INTO Subcategory VALUES(63,'Winter', 7); 
INSERT INTO Subcategory VALUES(64,'Summer', 7); 
INSERT INTO Subcategory VALUES(65,'Rain coats', 7); 
-- For woman shoes 
INSERT INTO Subcategory VALUES(66,'Sneakers', 8); 
INSERT INTO Subcategory VALUES(67,'Boots', 8); 
INSERT INTO Subcategory VALUES(68,'Heels', 8);
INSERT INTO Subcategory VALUES(69,'Sandals', 8); 


-- For man 
-- For man shirts 
INSERT INTO Subcategory VALUES(70,'Long Sleeve', 9); 
INSERT INTO Subcategory VALUES(71,'Mid Sleeve', 9); 
INSERT INTO Subcategory VALUES(72,'Short Sleeve', 9); 
-- For man jeans 
INSERT INTO Subcategory VALUES(73,'Lose Fit', 10); 
INSERT INTO Subcategory VALUES(74,'Skinny Fit', 10); 
-- For man pants 
INSERT INTO Subcategory VALUES(75,'Lose Fit', 11);
INSERT INTO Subcategory VALUES(76,'Skinny Fit', 11);
-- For man shorts 
INSERT INTO Subcategory VALUES(103,'Denim', 38); 
INSERT INTO Subcategory VALUES(78,'Fabric', 38); 
-- For man swimwear 
INSERT INTO Subcategory VALUES(79,'Shorts', 12); 
-- For man coats
INSERT INTO Subcategory VALUES(80,'Winter', 13); 
INSERT INTO Subcategory VALUES(81,'Summer', 13); 
INSERT INTO Subcategory VALUES(82,'Rain Coats', 13); 
-- For man shoes 
INSERT INTO Subcategory VALUES(83,'Sneakers', 14); 
INSERT INTO Subcategory VALUES(84,'Boots', 14); 
INSERT INTO Subcategory VALUES(85,'Sandals', 14); 
INSERT INTO Subcategory VALUES(86,'Loafers', 14); 


-- For kids 
-- For girls
INSERT INTO Subcategory VALUES(87,'Dresses', 15); -- 
INSERT INTO Subcategory VALUES(88,'Tops', 15); --
INSERT INTO Subcategory VALUES(89,'Jeans', 15);
INSERT INTO Subcategory VALUES(90,'Skirts', 15);
INSERT INTO Subcategory VALUES(91,'Shorts', 15);
INSERT INTO Subcategory VALUES(92,'Pants', 15);
INSERT INTO Subcategory VALUES(93,'Swimwear', 15);
INSERT INTO Subcategory VALUES(94,'Coats', 15); --
INSERT INTO Subcategory VALUES(95,'Shoes', 15);

-- for boys 
INSERT INTO Subcategory VALUES(96,'Tops', 16); --
INSERT INTO Subcategory VALUES(97,'Jeans', 16);
INSERT INTO Subcategory VALUES(98,'Shorts', 16);
INSERT INTO Subcategory VALUES(99,'Pants', 16);
INSERT INTO Subcategory VALUES(100,'Swimwear', 16);
INSERT INTO Subcategory VALUES(101,'Coats', 16); --
INSERT INTO Subcategory VALUES(102,'Shoes', 16);



--Women Items
INSERT INTO Item VALUES (1, 3,
  'Gucci Coat',
  'beige coat',
  1,7, 
  'Gucci', 'L', 'Beige',
  'Excelent', 400.00,
  '../images/items/item1.png');
INSERT INTO Item VALUES (2, 5,
  'Jimmy Choo Heels',
  'pink shoes',
  1,8,
  'Jimmy Choo', 'M', 'Pink',
  'Very good', 450.00,
  '../images/items/item2.png');
INSERT INTO Item VALUES (3, 2,
  'Balenciaga Jeans',
  'beautiful jeans',
  1, 3,
  'Balenciaga', 'XS', 'Blue',
  'Very good', 910.00,
  '../images/items/item3.png');
INSERT INTO Item VALUES (4, 7,
  'Dior skirt',
  'beautiful skirt',
  1, 4,
  'Dior', 'L', 'Blue',
  'Very good', 725.00,
  '../images/items/item4.png');
INSERT INTO Item VALUES (5, 2,
  'Burberry Dress',
  'very good quality',
  1,1,
  'Burberry', 'M', 'Brown',
  'Excelent', 545.00,
  '../images/items/item5.png');
INSERT INTO Item VALUES (19, 9,
  'Miu Miu Top',
  'Navy blue top',
  1,2,
  'Burberry', 'M', 'Blue',
  'Bad', 100.00,
  '../images/items/item19.png');
  INSERT INTO Item VALUES (20, 6,
  'Fendi Trousers',
  'Black trousers',
  1,5,
  'Fendi', 'L', 'Black',
  'Excellent', 700.00,
  '../images/items/item20.png');
  INSERT INTO Item VALUES (21, 2,
  'Dior Swimsuit',
  'Pink swimsuit',
  1,6,
  'Dior', 'M', 'Pink',
  'Bad', 100.00,
  '../images/items/item21.png');
  INSERT INTO Item VALUES (22, 2,
  'Dolce&Gabbana Shorts',
  'Animal print shorts',
  1,37,
  'Dolce&Gabbana', 'L', 'Brown',
  'Bad', 90.00,
  '../images/items/item22.png');
--Men Items
INSERT INTO Item VALUES (6, 10,
  'Prada Jacket',
  'green jacket',
  2, 13,
  'Prada', 'S', 'Green',
  'Very Good', 550.00,
  '../images/items/item6.png');
INSERT INTO Item VALUES (7, 2,
  'Louis Vuitton Sneakers',
  'white sneakers',
  2, 14, 
  'Louis Vuitton', '43', 'White',
  'Good', 500.00,
  '../images/items/item7.png');
INSERT INTO Item VALUES (8, 5,
  'Gucci Shirt',
  'white shirt',
  2, 9,
  'Gucci', 'M', 'White',
  'Bad', 150.00,
  '../images/items/item8.png');
INSERT INTO Item VALUES (23, 1,
  'Gucci Jeans',
  'Light jeans',
  2, 10,
  'Gucci', 'XL', 'Blue',
  'Very good', 300.00,
  '../images/items/item23.png');
INSERT INTO Item VALUES (24, 4,
  'Jacquemus Trousers',
  'Grey Trousers',
  2, 11,
  'Jacquemus', 'XS', 'Grey',
  'Very good', 415.00,
  '../images/items/item24.png');
INSERT INTO Item VALUES (25, 6,
  'Armani Shorts',
  'Beige shorts',
  2, 38,
  'Armani', 'S', 'Beige',
  'Good', 95.00,
  '../images/items/item25.png');
INSERT INTO Item VALUES (26, 8,
  'Prada Swim Shorts',
  'Green shorts',
  2, 12,
  'Prada', 'S', 'Green',
  'Good', 95.00,
  '../images/items/item26.png');
--Kids Items
INSERT INTO Item VALUES (9, 3,
  'Blouse Burberry',
  'White top',
  3, 16,
  'Burberry', 'S', 'White',
  'Good', 510.00,
  '../images/items/item9.png');
INSERT INTO Item VALUES (10, 8,
  'Moncler Jacket',
  'pink jacket',
  3, 21,
  'Moncler', 'S', 'Pink',
  'Good', 120.00,
  '../images/items/item10.png');
INSERT INTO Item VALUES (27, 8,
  'Gucci dress',
  'pink dress',
  3, 15,
  'Gucci', 'L', 'Pink',
  'Very good', 700.00,
  '../images/items/item27.png');
--Jewerly Items
INSERT INTO Item VALUES (11, 7,
  'Swarovski Necklace',
  'diamond necklace',
  4, 24,
  'Swarovski', 'S', 'Silver',
  'Very Good', 2500.00,
  '../images/items/item11.png');
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
  'Cartier', '-', 'Silver',
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
  'Blue hat',
  6, 33,
  'Polo Ralph Lauren', 'S', 'Blue',
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
  'Black small wallet',
  6,35,
  'Louis Vuitton', 'Small', 'Black',
  'Very good', 110.00,
  'image');
INSERT INTO Item VALUES (34, 10,
  'Jacquemus belt',
  'Black simple belt',
  6,31,
  'Jacquemus', '-', 'Black',
  'Good', 120.00,
  'image');
INSERT INTO Item VALUES (35, 8,
  'Acne studios wool',
  'Pink wool scarf',
  6,34,
  'Acne Studios', '-', 'Pink',
  'Good', 128.00,
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
INSERT INTO Review VALUES (2, 10, 4, 16, 5.0, "Excellent");
INSERT INTO Review VALUES (3, 7, 10, 11, 3.5, "");
INSERT INTO Review VALUES (4, 1, 5, 4, 5.0, "Nice!");
INSERT INTO Review VALUES (5, 7, 4, 7, 5.0, "");