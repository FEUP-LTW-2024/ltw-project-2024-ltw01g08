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
    profile_picture url 
);

DROP TABLE IF EXISTS Admin;

CREATE TABLE Admin (
    user_id INTEGER PRIMARY KEY REFERENCES User(id)
);

DROP TABLE IF EXISTS Department;

CREATE TABLE Department (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    d_name TEXT NOT NULL UNIQUE
);

DROP TABLE IF EXISTS Category;

CREATE TABLE Category (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  c_name TEXT NOT NULL,
  department_id INTEGER NOT NULL REFERENCES Department(id)
);

DROP TABLE IF EXISTS Subcategory;

CREATE TABLE Subcategory (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  subc_name TEXT NOT NULL,
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
  subcategory_id INTEGER REFERENCES Subcategory(id),
  brand TEXT NOT NULL,
  item_size TEXT NOT NULL,
  color TEXT NOT NULL,
  condition TEXT NOT NULL,
  price DECIMAL(10, 2),
  image_url TEXT
);

DROP TABLE IF EXISTS "Transaction";

CREATE TABLE "Transaction" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    buyer_id INTEGER NOT NULL REFERENCES User(id),
    seller_id INTEGER NOT NULL REFERENCES User(id),
    item_id INTEGER NOT NULL REFERENCES Item(id),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(10, 2)
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
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  seller_id INTEGER NOT NULL REFERENCES User(id),
  reviewer_id INTEGER NOT NULL REFERENCES User(id),
  item_id INTEGER NOT NULL REFERENCES Item(id),
  rating DECIMAL(2,1) CHECK (rating >= 0 AND rating <= 5),
  comment TEXT,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS Cart;

DROP TABLE IF EXISTS Cart;

CREATE TABLE Cart (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  item_id INTEGER NOT NULL,
  amount INTEGER NOT NULL DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES User(id),
  FOREIGN KEY (item_id) REFERENCES Item(id)
);



DROP TABLE IF EXISTS PendingOrder;

CREATE TABLE PendingOrder (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES User(id),
    item_id INTEGER NOT NULL REFERENCES Item(id),
    amount INTEGER NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    from_user_id INTEGER NOT NULL,
    to_user_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES User(id),
    FOREIGN KEY (to_user_id) REFERENCES User(id),
    FOREIGN KEY (product_id) REFERENCES Item(id)
);



INSERT INTO User VALUES (110, 'Dominic', 'Woods', 'dominic111','dominic055@hotmail.com', 'dominic22', 'address', 'profile picture url');
INSERT INTO User VALUES (111, 'Zachary', 'Young', 'zacharyy', 'zacharyyoung@gmail.com', '7110eda4d09e062', 'address', 'profile picture url');
INSERT INTO User VALUES (112, 'Alicia', 'Hamilton', 'alicia', 'aliciahamilton@gmail.com', '711222', 'address', 'profile picture url');
INSERT INTO User VALUES (113, 'Pedro', 'Silva', 'pedro22', 'silvapedro1@hotmail.com', '2c0220',  'address', 'profile picture url');
INSERT INTO User VALUES (114, 'Maria', 'Vieira', 'maria_v', 'mariav@hotmail.com', 'maria111',  'address', 'profile picture url');
INSERT INTO User VALUES (115, 'Luís', 'Marques', 'marquesluis', 'marquesl@hotmail.com', 'ml2001',  'address', 'profile picture url');
INSERT INTO User VALUES (116, 'Pedro', 'Silva', 'pedro221', 'silvatpedro1@hotmail.com', 'pedropedro',  'address', 'profile picture url');
INSERT INTO User VALUES (117, 'Márcia', 'Correia', 'marcialuisac', 'marciac@hotmail.com', 'marciaa',  'address', 'profile picture url');
INSERT INTO User VALUES (118, 'João', 'Cruz', 'jcruzj', 'jcruzz@hotmail.com', 'cruz222',  'address', 'profile picture url');
INSERT INTO User VALUES (119, 'Lara', 'Almeida', 'laraalmeida9', 'laralmeida@hotmail.com', '2018EDU',  'address', 'profile picture url');
INSERT INTO User VALUES (120, 'Admin', 'Admin', 'admin1', 'admin1@hotmail.com', 'adminp',  'address', 'profile picture url');

INSERT INTO Admin VALUES(120);

INSERT INTO Department VALUES(122, 'WOMEN');
INSERT INTO Department VALUES(123, 'MEN');
INSERT INTO Department VALUES(124, 'KIDS');
INSERT INTO Department VALUES(125, 'JEWELRY');
INSERT INTO Department VALUES(126, 'BAGS');
INSERT INTO Department VALUES(127, 'ACCESSORIES');

INSERT INTO Category VALUES(128,'Dresses', 122); --
INSERT INTO Category VALUES(129,'Tops', 122); --
INSERT INTO Category VALUES(130,'Jeans', 122); --
INSERT INTO Category VALUES(131,'Skirts', 122); --
INSERT INTO Category VALUES(132,'Shorts', 122); --
INSERT INTO Category VALUES(133,'Pants', 122); --
INSERT INTO Category VALUES(134,'Swimwear', 122); --
INSERT INTO Category VALUES(135,'Coats', 122); --
INSERT INTO Category VALUES(136,'Shoes', 122); --

INSERT INTO Category VALUES(137,'Shirts', 123); --
INSERT INTO Category VALUES(138,'Jeans', 123); --
INSERT INTO Category VALUES(139,'Pants', 123); --
INSERT INTO Category VALUES(140,'Shorts', 123); --
INSERT INTO Category VALUES(141,'Swimwear', 123); --
INSERT INTO Category VALUES(142,'Coats', 123); --
INSERT INTO Category VALUES(143,'Shoes', 123); --

--INSERT INTO Category VALUES(15,'Dresses', 3); -- 
--INSERT INTO Category VALUES(16,'Tops', 3); --
--INSERT INTO Category VALUES(17,'Jeans', 3);
--INSERT INTO Category VALUES(18,'Skirts', 3);
--INSERT INTO Category VALUES(39,'Shorts', 3);
--INSERT INTO Category VALUES(19,'Pants', 3);
--INSERT INTO Category VALUES(20,'Swimwear', 3);
--INSERT INTO Category VALUES(21,'Coats', 3); --
--INSERT INTO Category VALUES(22,'Shoes', 3);

INSERT INTO Category VALUES(15,'Girl', 124); -- 
INSERT INTO Category VALUES(16,'Boy', 124); --



INSERT INTO Category VALUES(17,'Rings', 125); --
INSERT INTO Category VALUES(18,'Necklaces', 125); -- 
INSERT INTO Category VALUES(19,'Earrings', 125); -- 
INSERT INTO Category VALUES(20,'Bracelets', 125); --

INSERT INTO Category VALUES(21,'Shoulder Bags', 126); --
INSERT INTO Category VALUES(22,'Handbags', 126); -- 
INSERT INTO Category VALUES(23,'Crossbody Bags', 126); --
INSERT INTO Category VALUES(24,'Clutch Bags', 126); --

INSERT INTO Category VALUES(25,'Belts', 127); -- 
INSERT INTO Category VALUES(26,'Sunglasses', 127); -- 
INSERT INTO Category VALUES(27,'Hats', 127); --
INSERT INTO Category VALUES(28,'Scarfs', 127); -- 
INSERT INTO Category VALUES(29,'Wallets', 127); -- 
INSERT INTO Category VALUES(30,'Watches', 127); -- 

-- SUBCATEGORIES!!!!!!!!
--For Woman dresses
INSERT INTO Subcategory VALUES(41,'Mini', 128); 
INSERT INTO Subcategory VALUES(42,'Midi', 128);
INSERT INTO Subcategory VALUES(43,'Maxi', 128); 
--For Woman tops
INSERT INTO Subcategory VALUES(44,'Blouses', 129); 
INSERT INTO Subcategory VALUES(45,'Crop tops', 129); 
INSERT INTO Subcategory VALUES(46,'Shirts', 129); 
INSERT INTO Subcategory VALUES(47,'T-Shirts', 129); 
--For woman jeans
INSERT INTO Subcategory VALUES(48,'Loose fit', 130); 
INSERT INTO Subcategory VALUES(49,'Skinny Fit', 130); 
INSERT INTO Subcategory VALUES(50,'Bootcut fit', 130); 
-- For woman skirts
INSERT INTO Subcategory VALUES(51,'Mini', 131); 
INSERT INTO Subcategory VALUES(52,'Midi', 131); 
INSERT INTO Subcategory VALUES(53,'Maxi', 131); 
-- For woman shorts
INSERT INTO Subcategory VALUES(54,'Short length', 132); 
INSERT INTO Subcategory VALUES(55,'Mid length', 132); 
-- For woman pants
INSERT INTO Subcategory VALUES(56,'Loose fit', 133); 
INSERT INTO Subcategory VALUES(57,'Skinny fit', 133); 
INSERT INTO Subcategory VALUES(58,'Bootcut fit', 133); 
-- For woman Swimwear 
INSERT INTO Subcategory VALUES(59,'Bikini', 134); 
INSERT INTO Subcategory VALUES(62,'One-Piece', 134); 
-- For woman coats 
INSERT INTO Subcategory VALUES(63,'Winter', 135); 
INSERT INTO Subcategory VALUES(64,'Summer', 135); 
INSERT INTO Subcategory VALUES(65,'Rain coats', 135); 
-- For woman shoes 
INSERT INTO Subcategory VALUES(66,'Sneakers', 136); 
INSERT INTO Subcategory VALUES(67,'Boots', 136); 
INSERT INTO Subcategory VALUES(68,'Heels', 136);
INSERT INTO Subcategory VALUES(69,'Sandals', 136); 


-- For man 
-- For man shirts 
INSERT INTO Subcategory VALUES(70,'Long Sleeve', 137); 
INSERT INTO Subcategory VALUES(71,'Mid Sleeve', 137); 
INSERT INTO Subcategory VALUES(72,'Short Sleeve', 137); 
-- For man jeans 
INSERT INTO Subcategory VALUES(73,'Lose Fit', 138); 
INSERT INTO Subcategory VALUES(74,'Skinny Fit', 138); 
-- For man pants 
INSERT INTO Subcategory VALUES(75,'Lose Fit', 139);
INSERT INTO Subcategory VALUES(76,'Skinny Fit', 139);
-- For man shorts 
INSERT INTO Subcategory VALUES(103,'Denim', 140); 
INSERT INTO Subcategory VALUES(78,'Fabric', 140); 
-- For man swimwear 
INSERT INTO Subcategory VALUES(79,'Shorts', 141); 
-- For man coats
INSERT INTO Subcategory VALUES(80,'Winter', 142); 
INSERT INTO Subcategory VALUES(81,'Summer', 142); 
INSERT INTO Subcategory VALUES(82,'Raincoats', 142); 
-- For man shoes 
INSERT INTO Subcategory VALUES(83,'Sneakers', 143); 
INSERT INTO Subcategory VALUES(84,'Boots', 143); 
INSERT INTO Subcategory VALUES(85,'Sandals', 143); 
INSERT INTO Subcategory VALUES(86,'Loafers', 143); 


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
INSERT INTO Item VALUES (1, 110,
  'Gucci Coat',
  'beige coat',
  122,135, 63,
  'Gucci', 'L', 'Beige',
  'ExcelLent', 400.00,
  '../images/items/item1_1.png');
INSERT INTO Item VALUES (2, 112,
  'Jimmy Choo Heels',
  'pink shoes',
  122,136, 68,
  'Jimmy Choo', 'M', 'Pink',
  'Very good', 450.00,
  '../images/items/item2_1.png');
INSERT INTO Item VALUES (3, 112,
  'Balenciaga Jeans',
  'beautiful jeans',
  122, 130, 48,
  'Balenciaga', 'XS', 'Blue',
  'Very good', 910.00,
  '../images/items/item3_1.png');
INSERT INTO Item VALUES (4, 117,
  'Dior skirt',
  'beautiful skirt',
  122, 131, 52,
  'Dior', 'L', 'Blue',
  'Very good', 725.00,
  '../images/items/item4_1.png');
INSERT INTO Item VALUES (5, 117,
  'Burberry Dress',
  'very good quality',
  122,128, 42,
  'Burberry', 'M', 'Brown',
  'ExcelLent', 545.00,
  '../images/items/item5_1.png');
INSERT INTO Item VALUES (19, 116,
  'Miu Miu Top',
  'Navy blue top',
  122,129, 45,
  'Miu Miu', 'M', 'Blue',
  'Bad', 100.00,
  '../images/items/item19_1.png');
  INSERT INTO Item VALUES (20, 115,
  'Fendi Trousers',
  'Black trousers',
  122,133, 57,
  'Fendi', 'L', 'Black',
  'Excellent', 700.00,
  '../images/items/item20_1.png');
  INSERT INTO Item VALUES (21, 118,
  'Dior Swimsuit',
  'Pink swimsuit',
  122,134, 62,
  'Dior', 'M', 'Pink',
  'Bad', 100.00,
  '../images/items/item21_1.png');
  INSERT INTO Item VALUES (22, 112,
  'Dolce&Gabbana Shorts',
  'Animal print shorts',
  122,132, 54,
  'Dolce&Gabbana', 'L', 'Brown',
  'Bad', 90.00,
  '../images/items/item22_1.png');

--Men Items
INSERT INTO Item VALUES (6, 113,
  'Prada Jacket',
  'green jacket',
  123, 142, 82,
  'Prada', 'S', 'Green',
  'Very Good', 550.00,
  '../images/items/item6_1.png');
INSERT INTO Item VALUES (7, 114,
  'Louis Vuitton Sneakers',
  'white sneakers',
  123, 143, 83,
  'Louis Vuitton', '43', 'White',
  'Good', 500.00,
  '../images/items/item7_1.png');
INSERT INTO Item VALUES (8, 114,
  'Gucci Shirt',
  'white shirt',
  123, 137, 70,
  'Gucci', 'M', 'White',
  'Bad', 150.00,
  '../images/items/item8_1.png');
INSERT INTO Item VALUES (23, 111,
  'Gucci Jeans',
  'Light jeans',
  123, 138, 73,
  'Gucci', 'XL', 'Blue',
  'Very good', 300.00,
  '../images/items/item23_1.png');
INSERT INTO Item VALUES (24, 110,
  'Jacquemus Trousers',
  'Grey Trousers',
  123, 139, 75,
  'Jacquemus', 'XS', 'Grey',
  'Very good', 415.00,
  '../images/items/item24_1.png');
INSERT INTO Item VALUES (25, 110,
  'Armani Shorts',
  'Beige shorts',
  123, 140, 78,
  'Armani', 'S', 'Beige',
  'Good', 95.00,
  '../images/items/item25_1.png');
INSERT INTO Item VALUES (26, 118,
  'Prada Swim Shorts',
  'Green shorts',
  123, 141, 79,
  'Prada', 'S', 'Green',
  'Good', 95.00,
  '../images/items/item26_1.png');
--Kids Items
INSERT INTO Item VALUES (9, 114,
  'Blouse Burberry',
  'White top',
  124, 15, 88,
  'Burberry', 'S', 'White',
  'Good', 510.00,
  '../images/items/item9_1.png');
INSERT INTO Item VALUES (10, 113,
  'Moncler Jacket',
  'pink jacket',
  124, 15, 94,
  'Moncler', 'S', 'Pink',
  'Good', 120.00,
  '../images/items/item10_1.png');
INSERT INTO Item VALUES (27, 116,
  'Gucci dress',
  'pink dress',
  124, 15, 87,
  'Gucci', 'L', 'Pink',
  'Very good', 700.00,
  '../images/items/item27_1.png');
--Jewerly Items
INSERT INTO Item VALUES (11, 115,
  'Swarovski Necklace',
  'diamond necklace',
  125, 24, NULL,
  'Swarovski', 'S', 'Silver',
  'Very Good', 2500.00,
  '../images/items/item11_1.png');
INSERT INTO Item VALUES (12, 115,
  'Cartier Ring',
  'gold ring',
  125, 23, NULL ,
  'Cartier', '17', 'Gold',
  'Good', 1000.00,
  '../images/items/item12_1.png');
INSERT INTO Item VALUES (28, 111,
  'Tiffany&Co Earrings',
  'gold earrings',
  125, 25, NULL,
  'Tiffany&Co', '-', 'Gold',
  'Good', 900.00,
  '../images/items/item28_1.png');
INSERT INTO Item VALUES (29, 112,
  'Van Cleef Bracelet',
  'gold gorgeous bracelet',
  125, 26, NULL,
  'Van Cleef', '-', 'Gold',
  'Excellent', 2300.00,
  '../images/items/item29_1.png');
INSERT INTO Item VALUES (30, 117,
  'Cartier Bracelet',
  'silver bracelet',
  125, 26, NULL,
  'Cartier', '-', 'Silver',
  'Bad', 900.00,
  '../images/items/item30_1.png');

--Bags 
INSERT INTO Item VALUES (13, 115,
  'Prada Purse',
  'black purse',
  126, 21, NULL,
  'Prada', 'S', 'Black',
  'Excelent', 870.00,
  '../images/items/item13_1.png');
INSERT INTO Item VALUES (14, 116,
  'Hermes Birkin Bag',
  'Birkin bag',
  126, 22, NULL,
  'Hermes', 'Medium', 'Orange',
  'Good', 10000.00,
  '../images/items/item14_1.png');
INSERT INTO Item VALUES (15, 112,
  'Chanel Purse',
  'pink purse',
  126, 22, NULL,
  'Chanel', 'Large', 'Pink',
  'Very good', 800.00,
  '../images/items/item15_1.png');
INSERT INTO Item VALUES (31, 111,
  'Chloé Clutch',
  'Gold purse',
  126, 24, NULL,
  'Chanel', 'Small', 'Gold',
  'Bad', 500.00,
  '../images/items/item31_1.png');

--Accessories
INSERT INTO Item VALUES (16, 113,
  'Rolex Watch',
  'stylish rolex watch',
  127, 30, NULL,
  'Rolex', '6', 'Silver',
  'Very Good', 25000.00,
  '../images/items/item16_1.png');
INSERT INTO Item VALUES (17, 113,
  'YSL Sunglasses',
  'beautiful sunglasses',
  127, 26, NULL,
  'Yves Saint Laurent', 'S', 'Black',
  'Good', 300.00,
  '../images/items/item17_1.png');
INSERT INTO Item VALUES (32, 114,
  'Polo Ralph Lauren hat',
  'Blue hat',
  127, 27, NULL,
  'Polo Ralph Lauren', 'S', 'Blue',
  'Good', 150.00,
  '../images/items/item32_1.png');
INSERT INTO Item VALUES (18, 117,
  'Burberry Scarf',
  'very good quality scarf',
  127,28, NULL,
  'Burberry', 'M', 'Brown',
  'Excelent', 260.00,
  '../images/items/item18_1.png');
INSERT INTO Item VALUES (33, 112,
  'Louis Vuitton wallet',
  'Black small wallet',
  127,29, NULL,
  'Louis Vuitton', 'Small', 'Black',
  'Very good', 110.00,
  '../images/items/item33_1.png');
INSERT INTO Item VALUES (34, 110,
  'Jacquemus belt',
  'Black simple belt',
  127,25, NULL,
  'Jacquemus', '-', 'Black',
  'Good', 120.00,
  '../images/items/item34_1.png');
INSERT INTO Item VALUES (35, 111,
  'Acne studios wool',
  'Pink wool scarf',
  127,28, NULL,
  'Acne Studios', '-', 'Pink',
  'Good', 128.00,
  '../images/items/item35_1.png');


  

INSERT INTO "Transaction" VALUES (200, 119, 112, 2, "2022-12-20 10:30:00", 450.00);
INSERT INTO "Transaction" VALUES (201, 110, 113, 16, "2023-03-02 10:30:00", 24000.00);
INSERT INTO "Transaction" VALUES (202, 112 , 115, 11, "2023-01-17 10:30:00", 2500.00);
INSERT INTO "Transaction" VALUES (203, 118, 117, 4, "2023-04-14 10:30:00", 725.00);
INSERT INTO "Transaction" VALUES (204, 115, 114, 7, "2023-02-21 10:30:00", 500.00);

