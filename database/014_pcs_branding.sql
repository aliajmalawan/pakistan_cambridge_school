USE pakistan_cambridge_school;
UPDATE settings SET svalue='Pakistan Cambridge School' WHERE skey='site_name';
UPDATE settings SET svalue='پاکستان کیمبرج سکول، حافظ آباد' WHERE skey='site_name_ur';
UPDATE settings SET svalue='Learning Today. Leading Tomorrow.' WHERE skey='tagline';
UPDATE settings SET svalue='Hafizabad, Punjab, Pakistan' WHERE skey='address';
UPDATE settings SET svalue='' WHERE skey IN ('facebook','youtube','phone','email','whatsapp','map_embed');
UPDATE settings SET svalue='Building confident learners, responsible citizens and future leaders through purposeful education.' WHERE skey='mission';
UPDATE settings SET svalue='To create a learning community where academic excellence, character, confidence and leadership grow together.' WHERE skey='vision';
UPDATE settings SET svalue='Pakistan Cambridge School Hafizabad is committed to a balanced education that develops strong academic foundations, character and future-ready skills.' WHERE skey='footer_about';
DELETE FROM sliders;
INSERT INTO sliders (eyebrow,title,subtitle,cta_text,cta_link,sort_order,status) VALUES
('Pakistan Cambridge School • Hafizabad','Where ambition meets education.','A modern school community where academic excellence, character, confidence and leadership grow together.','Explore Our School','#about',1,'published'),
('Academic Pathways','Learning with purpose.','Structured pathways help students build knowledge step by step while developing the confidence to apply it.','Explore Academics','#academics',2,'published'),
('Student Life','Strong minds. Stronger character.','Sports, clubs, competitions and leadership experiences help students discover their strengths beyond the classroom.','Discover Student Life','#life',3,'published');
