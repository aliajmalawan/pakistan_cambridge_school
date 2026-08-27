-- Pakistan Cambridge School — Public Pages + Navigation
USE pakistan_cambridge_school;

UPDATE pages
SET title = REPLACE(REPLACE(REPLACE(title, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School'), 'About Kohsar', 'About Pakistan Cambridge School'),
    lede = REPLACE(REPLACE(lede, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar', 'Pakistan Cambridge School'),
    content = REPLACE(REPLACE(REPLACE(content, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School'), 'Kohsar', 'Pakistan Cambridge School'),
    meta_description = REPLACE(REPLACE(meta_description, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School');

UPDATE pages SET show_in_menu = 1 WHERE status = 'published';

SET @main := (SELECT id FROM menus WHERE slug = 'main' LIMIT 1);
DELETE FROM menu_items WHERE menu_id = @main;

INSERT INTO menu_items (menu_id,label,link_type,route,sort_order,status) VALUES
(@main,'Home','route','/',10,'published'),
(@main,'About','none','',20,'published'),
(@main,'Academics','none','',30,'published'),
(@main,'Campus & Facilities','none','',40,'published'),
(@main,'News & Notices','route','/news',50,'published'),
(@main,'Admissions','route','/admissions',60,'published'),
(@main,'Downloads','route','/downloads',70,'published'),
(@main,'Contact','route','/contact',80,'published');

SET @about := (SELECT id FROM menu_items WHERE menu_id=@main AND label='About' ORDER BY id DESC LIMIT 1);
SET @academics := (SELECT id FROM menu_items WHERE menu_id=@main AND label='Academics' ORDER BY id DESC LIMIT 1);
SET @campus := (SELECT id FROM menu_items WHERE menu_id=@main AND label='Campus & Facilities' ORDER BY id DESC LIMIT 1);

INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'About Us','page',id,@about,10,'published' FROM pages WHERE slug='about' LIMIT 1;
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Vision & Mission','route','/vision-mission',@about,20,'published');
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Leadership','route','/leadership',@about,30,'published');
INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'Rules & Discipline','page',id,@about,40,'published' FROM pages WHERE slug='rules' LIMIT 1;

INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status) VALUES
(@main,'Academic Overview','route','/academics',@academics,10,'published'),
(@main,'Academic Programs','route','/programs',@academics,20,'published'),
(@main,'Academic Calendar','route','/academic-calendar',@academics,30,'published'),
(@main,'Fee Structure','route','/fees',@academics,40,'published');

INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'Campus & Facilities','page',id,@campus,10,'published' FROM pages WHERE slug='facilities' LIMIT 1;
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Our Faculty','route','/faculty',@campus,20,'published');
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Gallery','route','/gallery',@campus,30,'published');
