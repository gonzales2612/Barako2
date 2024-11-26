CREATE TABLE order_items (
     OrderId INT,
     MenuItemId INT,
     Quantity INT NOT NULL,
     Subtotal DECIMAL(10, 2) NOT NULL,
     FOREIGN KEY (OrderId) REFERENCES orders(Id),
     FOREIGN KEY (MenuItemId) REFERENCES menu_items(Id),
     PRIMARY KEY (OrderId, MenuItemId) 
     );