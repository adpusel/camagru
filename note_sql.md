#intro
le sql est un lanquage relationnel cad que les donnes ont des relation entre elle, elles sonb agreger par ces relations \
mysql appartient a oracle et est dipo en deux versions, une gratuite et une payante, \
mysql utilise parfois une syntaxe qui lui est propres et eloigné de la norme AINSI

#le demarage: 
sur mon mac, mysql est instaler dans le panneau et demare tout seul
j'ai fait un alias pour my
pour les prochaines install, 
1. dl my sql et l'instaler
2. mettre phpmyadmin dans htc doc et le configurer le setup de phpmyadmin

#instalation et tips
une instruction se fini toujours pas ; \
les commentaires sont `-- ` \
le text doit etre uniquement entourer de guillements simple, je fais double '' pour echaper ce dernier \
```mysql
    CREATE USER 'tuto'@'localhost' IDENTIFIED BY 'pass';
	GRANT ALL PRIVILEGES ON Blog_grafikart.* TO 'tuto'@'localhost';

    CREATE USER 'student'  -- : cette commande crée l'utilisateur student.
    -- @'localhost'  : définit à partir d'où l'utilisateur peut se connecter. Dans notre cas, 'localhost', donc il devra être connecté à partir de cet ordinateur.

    IDENTIFIED BY 'mot_de_passe';  -- : définit le mot de passe de l'utilisateur.

    GRANT ALL PRIVILEGES  -- : cette commande permet d'attribuer tous les droits (c'est-à-dire insertions de données, sélections, 
    -- modifications, suppressions…).

    ON elevage.*  -- : définit les bases de données et les tables sur lesquelles ces droits sont acquis. Donc ici, on donne les droits sur la base "elevage" (qui n'existe pas encore, mais ce n'est pas grave, nous la créerons plus tard), pour toutes les tables de cette base (grâce à *).

    TO 'student'@'localhost' -- : définit l'utilisateur (et son hôte) auquel on accorde ces droits. 
```
je viens de me reconnecter avec un nouvelle user qui n'a pas les droits sur toute la base, faut pas deconner \

a chaque connextion je dois specifier l'ecodage, ` SET NAMES 'utf8';` ou je fais comme dans la ligne d'apres en le mettant dans la commande
###############    #####################

sudo ./mysql -u student -pmot_de_passe --default-character-set=utf8 -p elevage

###############   #########################

#les types de donnes
il faut avoir de bon type de donnne car
1. des petites donner dans de grosse case ca marche pas
2. plus rapide de faire des recherche sur des nombres que sur des str
3. les trie chamge en fonction du type
4. stocker les date en str empeche par exemple de les comparer

##les int || possibles de faire de l'unsigned
| TINYINT   | 1 | 128                 |   |   |
|-----------|---|---------------------|---|---|
| SMALLINT  | 2 | 32000               |   |   |
| MEDIUMINT | 3 | 8388608             |   |   |
| INT       | 4 | 2147483648          |   |   |
| BIGINT    | 8 | 9223372036854775808 |   |   | 

--> je peux ajouter int(NB) ==> n'affichera que le nb de chiffre specifier dans NB \
--> INT(4) ZEROFILL --> zerofill met des zero pour arriver a la bonne taille --> 4 == 0004


##les decimaux
1. DECIMAL ( precision, echelle ) | NUMERIC (precision, nb_sinificatif) \
	precision --> nb de chiffre en tout, zerofill compte pas \
	echelle => nb de chiffre apres la virgule \
	si trop de chiffre ap
2. FLOAT | DOUBLE | REAL --> stoque des valeurs aprocher, donc arrondi, faire avec NUMRERIC plus safe si possible

##les str

###les chars
1.	les char --> prennent pile la place demander || si utf-8, le char pourra faire plus que la taille de 5 chars pour 5 utf-8
2. 	les vchar --> prennent la place demander + 1 octet pour la longeur de la chaine 

| texte  | mem char (5) | mem varchar (6) |
|--------|--------------|-----------------|
| ""     | 5            | 1               |
| 01     | 5            | 3               |
| 012345 | 5            | 6               | 

###les text (tout ce qui est plus grand que 255 char)

| type       | longueur max | mem        |
|------------|--------------|------------|
| TINYTEXT   | 2 ^ 8        | lenght + 1 |
| TEXT       | 2 ^ 16       | lenght + 2 |
| MEDIUMTEXT | 2 ^ 24       | lenght + 3 |
| LONGTEXT   | 2 ^ 32       | lenght + 4 |


### les chaine binaire --> pour les photos par exemple
comme pour les chaines str sauf qu'elle s'appelle --> TINYBLOB BLOB MEDIUMBLOB LONGBLOB

##les data temporel

##les heure 
le timestamp est un format pourri de mysql il est de la forme AAAAMMJJHHMMSS, le stocker dans un int semble plus smart ....

#creation de la base de donnee
--> jamais d'espace ou d'accent dans les noms des tables \
--> certain mot sont reserver au langage \
--> faire attention a l'utilisation des majuscule \
--> afficher les warnigs : 	``` SHOW WARNINGS; ```
creer une database : 		``` CREATE DATABASE elevage CHARACTER SET 'utf8'; ```\
drop database : 			``` DROP DATABASE elevage; ``` \
drop database if exist : 	``` DROP DATABASE IF EXISTS elevage; ``` \
selectioner la db		:   ```USE elevage```

#creation de tab
on stock l'id des ligne dans un unsigned int \
ce sera une primary key, la clee primaire de ma table, elle est unique et s'incremente toute seuls --> AUTO_INCREMENT \

##engine
sql possede deux engine, prendre INNODB car il fait plus de choses \
exemple de creation d'une table : ici je ne precise pas tout de suite la cle primaire \
null est par default authoriser \

```sql
CREATE TABLE [IF NOT EXISTS] Nom_table (
    colonne1 description_colonne1,
    [colonne2 description_colonne2,
    colonne3 description_colonne3,
    ...,]
    [PRIMARY KEY (colonne_clé_primaire)]
)
[ENGINE=moteur];
```
la commande qui va generer nos table :
```sql
CREATE TABLE Animal (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    espece VARCHAR(40) NOT NULL,
    sexe CHAR(1),
    date_naissance DATETIME NOT NULL,
    nom VARCHAR(30),
    commentaires TEXT,
    PRIMARY KEY (id)
)
ENGINE=INNODB;
```
possible de declarer une valeur par default avec, cet valeur doit etre une constante, pas comme NOW() par ex
```sql 
espece VARCHAR(40) NOT NULL DEFAULT 'chien'
```
commande gratuite :
```sql
SHOW TABLE; --donne toute les tables
DESCRIBE Animal; -- liste les colomne et leurs utiliter dans animal
```
```sql
SHOW TABLE; --donne toute les tables
DROP TABLE NAME_TABLE --me drop la table selectionne
```
#modification d'une table
c'est pas bien de modifier les tables, le faire que rarement !!!
on utilise forcement 

```mysql
ALTER TABLE nom_table ADD date_insertion DATE NOT NULL; -- permet d'ajouter quelque chose (une colonne par exemple)

ALTER TABLE nom_table DROP nom_colonne; -- permet de retirer quelque chose 

ALTER TABLE nom_table CHANGE  nom prenom VARCHAR(10) NOT NULL;
ALTER TABLE nom_table MODIFY col new_name ; -- permettent de modifier une colonne

```

#inseret des data
j'insere les data avec en ecrivant leurs col puis leurs values, il y a d'autre syntax mais je garde celle ci :)
```sql
INSERT INTO Animal (espece, sexe, date_naissance) 
    VALUES ('tortue', 'F', '2009-08-03 05:12:00');
INSERT INTO Animal (nom, commentaires, date_naissance, espece) 
    VALUES ('Choupi', 'Né sans oreille gauche', '2010-10-03 16:44:00', 'chat');
INSERT INTO Animal (espece, date_naissance, commentaires, nom, sexe) 
    VALUES ('tortue', '2009-06-13 08:17:00', 'Carapace bizarre', 'Bobosse', 'F');

ou en une seul col :
INSERT INTO Animal (espece, sexe, date_naissance, nom) 
VALUES ('chien', 'F', '2008-12-06 05:18:00', 'Caroline'),
        ('chat', 'M', '2008-09-11 15:38:00', 'Bagherra'),
        ('tortue', NULL, '2010-08-23 05:18:00', NULL);
```

#je peux get les data a partir d'un fichier :
**pour importer des fichiers depuis l'exterieur je dois set ```SET GLOBAL local_infile = 1;``` em root**
```sql
SOURCE Users\taguan\dossierX\monFichier.sql;
mais le plus souvent je fais  :

LOAD DATA [LOCAL] INFILE 'nom_fichier'
INTO TABLE nom_table
[FIELDS
    [TERMINATED BY '\t']    -- sepatateur
    [ENCLOSED BY '']        -- encadre les data
    [ESCAPED BY '\\' ]      -- char d'echapement
]
[LINES 
    [STARTING BY '']        -- debut des ligne
    [TERMINATED BY '\n']    -- fin des ligne
]
[IGNORE nombre LINES]       -- ignore nb ligne (nom des col en csv)
[(nom_colonne,...)];        --

-- exemple 

LOAD DATA LOCAL INFILE 'personne.csv'
INTO TABLE Personne
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n' -- ou '\r\n' selon l'ordinateur et le programme utilisés pour créer le fichier
IGNORE 1 LINES
(nom,prenom,date_naissance);

-- autre exemple
LOAD DATA LOCAL INFILE '/Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru/tuto_data.csv'
INTO TABLE Animal
FIELDS TERMINATED BY ';' ENCLOSED BY '"'
LINES TERMINATED BY '\n' -- ou '\r\n' selon l'ordinateur et le programme utilisés pour créer le fichier
(espece, sexe, date_naissance, nom, commentaires);  

```
#selectionner les donnes
c'est select qui permet de selectionner les donne
je fais :
```mysql
    SELECT  *
    FROM table
    WHERE condition = <condition>
```
##WHERE 
s'utilise avec des operateur de conparaison classique = <> ... \
j'utilise aussi les operateurs logique : AND OR XOR NOT / il est possible de les raccourcir mais c'est pas bien (pas supporter par tout les langages)

```sql
SELECT * 
FROM animal
WHERE 
    date_naissance >= '2010'
    OR
    (espece='chat' 
        AND 
        ( sexe='M'
        OR 
        ( sexe='F' AND date_naissance >= '2007-06' )
        )
    )    ;
```

## NULL
Nul est particulier car je ne peux le tester que avec <=> ou alors avec lettre :  \
pas possible de le tester sinon hahahaha :)
```sql
SELECT * 
FROM Animal 
WHERE nom IS NULL;
```

## le trie 
je peux trier les data en ajoutant `ORDER BY` apres le where, si where il y a :). 
```sql
SELECT * 
FROM Animal 
WHERE espece='chien' 
ORDER BY date_naissance, nom   ASC // DESC;
```
c'est possible de faire un trie descendant ou ascendant 
ou sur plusieur colomne, le trie ce fait dans l'ordre des colomne

## delete les doublons
si je fais cette request :
```sql
    SELECT espece FROM animal
```
je vais avoir les 500 chiens possibles et ca pas bien, \
      je fais : 
```sql
SELECT DISTINCT espece 
FROM Animal;
```
    et je get juste une ligne de chaque. 

## Limit
je peux limiter le nombre de ligne retourne par sql, offset est le depart 
```sql
LIMIT nombre_de_lignes [OFFSET decalage]; ==>

SELECT * 
FROM Animal 
ORDER BY id 
LIMIT 6 OFFSET 0;

SELECT * 
FROM Animal 
ORDER BY id 
LIMIT 6 OFFSET 3;

```

# Rendre whwre plus puissant

## recherche de str
l'opprateur like permet de faire des recherche comme avec des regex \
il possede deux jocker :    
==> % >> toute les chaines \
==> _ >> un seul char \
je dois echaper des char si je veux les chercher
```sql
    'b%'        --> tout ce qui commence oar b
    'b_'        --> tout ce qui fait deux lettre et commence par b
    '%ch%ne'    --> contient ch et fini par ne
// tout les animaux dont le nom contient PAS a

SELECT * 
FROM Animal 
WHERE nom NOT LIKE '%a%';

// pour rendre sensible a la casse je dois prendre une chaine bianire (si je suis en utf-8)

SELECT * 
FROM Animal 
WHERE nom LIKE '%Lu%'; -- insensible à la casse

SELECT * 
FROM Animal 
WHERE nom LIKE BINARY '%Lu%'; -- sensible à la casse
```

## recherche dans interval
j'utilise BETWEEN   \
```sql
SELECT * 
FROM Animal 
WHERE date_naissance BETWEEN '2008-01-05' AND '2009-03-23';
ou 
BETWEEN 0 AND 100
BETWEEN 'a' AND 'd' ==> interval ascii
ou
NOT BETWEEN 
```
si je veux definir un interval de possibiliter text je fais \
evite de faire une tonne de or :)
```sql
SELECT * 
FROM Animal 
WHERE nom IN ('Moka', 'Bilba', 'Tortilla', 'Balou', 'Dana', 'Redbul', 'Gingko');

```

#je peux faire des sauvegarde avec 
```bash
mysqldump -u root -phamhamham --opt elevage > elevage.sql
mysqldump -u user -p --opt nom_de_la_base > sauvegarde.sql
```
```sql
// pour charger une base sauvegarde 2 possibilitees;
 shell ==> mysql nom_base < chemin_fichier_de_sauvegarde.sql;

USE nom_base;
SOURCE fichier_de_sauvegarde.sql;
```

#la suppression des trucs
elle se fait de la meme maniere que la selection
```sql
DELETE FROM nom_table 
WHERE critères;
-- pour clear une table entierer je fait 
DELETE FROM Animal; et bin plus rien ...;

```

#la modification des data 
!! si je ne met pas de where la modif se fera sur toute la base...
!! toutjours modifier avec l'id 
```sql
UPDATE Animal 
SET sexe='F', nom='Pataude' 
WHERE id=21;
```

# les index
les index s'ajpute au table et permettent de ranger les choses. 
sql les ranges sous form d'arbres, il pointent vers les "struct ou sont les data" \
les index sont ranger en ordre croissant pour accelerer les recherches \
et l'algo doit etre vraiment pas mal \
seul les cles unique sont ranger dans l'order, c'est elle qui permettent de get les data \
les tables avec le plus de recherche doivent etre indexer pour les raison citer plus haut \
il ne faut pas en mettre partout car ils ralentisse les insertions (faut les updates) \

## manager les indexs
je peux creer un index sur qui range plusieur colomne, il les rangera dans l'ordre gauche a droite, /
c'est top si je reflechi car je peux en partant de la droite ( regarde la page https://openclassrooms.com/fr/courses/1959476-administrez-vos-bases-de-donnees-avec-mysql/1962880-index pour get le shema) permet de faire des recherche par la\
gauche, on ne fait que reprendre le meme index avec des donne en moins\
si je fais une recherche par la droite, je dois refaire des index car les donnes ne sont plus ranger pareils

## optimiser la recherche
sql va si je ne limite pas les caracteres de recherche, faire des comparaisons sur la totaliter de toute les string, \
ex ==> index sur titre de livre --> si je limit la recherche sur les 10 premiers charactere je devrait avoir les memes resultat en \
economisant de la resources\

## autres types d'index :

###les index unique 
ils peuvent etre sur une ou deux colomnes,\
et permettent de s'assurer que jamais je ne met deux fois la meme valeurs dans un col. \
    sur deux colmne ou plus : l'exemple de toto la tortue pas deux fois, mais toto le chien ok \
    **on dit que l'on ajoute une CONTRAINTE a la table** \

### les index full text
ils permettent de faire des recherche plus rapides sur les type texte, et ne marche que sur eux \
les fulltext ne supportent pas les recherche par la gauche, mais ok pour les multit colomne


## comment que on les creer ?
je met INDEX pour tout sauf PRIMARY KEY \
UNIQUE se declare tout seul. \
c'est mieux de definir les indexs une fois les table cree pour pouvoir en faire plusieur et c'est plus propre \

### avec les tables
```sql
CREATE TABLE Animal (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    espece VARCHAR(40) NOT NULL,
    sexe CHAR(1),
    date_naissance DATETIME NOT NULL,
    nom VARCHAR(30),
    commentaires TEXT,
    PRIMARY KEY (id),
    INDEX ind_date_naissance (date_naissance),  -- index sur la date de naissance
    INDEX ind_nom (nom(10))                     -- index sur le nom (le chiffre entre parenthèses étant le nombre de caractères pris en compte)
)
ENGINE=INNODB;
```
c'est mieux de preciser un index car sinon sql met le siens
je peux aussi mettre index sauf si je le met avec la colomne
```sql
CREATE TABLE Animal (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    espece VARCHAR(40) NOT NULL,
    sexe CHAR(1),
    date_naissance DATETIME NOT NULL,
    nom VARCHAR(30),
    commentaires TEXT,
    PRIMARY KEY (id),
    INDEX ind_date_naissance (date_naissance),  
    INDEX ind_nom (nom(10)),                    
    UNIQUE INDEX ind_uni_nom_espece (nom, espece)  -- Index sur le nom et l'espece
)
ENGINE=INNODB;
```

###en les reajoutant 
```sql
CREATE INDEX nom_index
ON nom_table (colonne_index [, colonne2_index ...]);  -- Crée un index simple

CREATE UNIQUE INDEX nom_index
ON nom_table (colonne_index [, colonne2_index ...]);  -- Crée un index UNIQUE


CREATE FULLTEXT INDEX nom_index
ON nom_table (colonne_index [, colonne2_index ...]);  -- Crée un index FULLTEXT
```

les index sont des containtes, quand j'ajoute un index je met indirectement une contrainte sur l'index, \
maie je peux aussi choirir la contraint, c'est inplicite avec index

!! je ne peux pas modifier un index je dois le drop et le remettre ensuite
```sql
ALTER TABLE nom_table 
DROP INDEX nom_index;
```

### Rappel sur les fulltext
la recherche full text c'est cool mais mieux de prendre une recherche sepcialiser si bca de donnee \
pour plus d'info lire le livre de open class room

#RESUMER
1. l'index est une stucture qui reprends de maniere ordonner les valeurs auquel il se raporte
2. un index peut se faire sur plusieur colomne
3. il peut se faire dans le cas d'un texte sur les x premier char
4. il permet d'accelerer les recherches sur la colomne qu'il indexifi
5. il peut aussi etre UNIQUE ou FULLTEXT

#faire des relation entre les tables

## la clee primaire
elle est unique et non null, et se def avec le mot clee : PRIMARY KEY \
les clee primaires sont deja des index. \
les clee primaires sont indispensable a toute les table sinon c'est du bullshit !!! \
je ne peux mettre que 1 PRIMARY KEY sur une table
comment on les fait ?
```sql
    CREATE TABLE Animal (
    id SMALLINT AUTO_INCREMENT,
    espece VARCHAR(40) NOT NULL,
    sexe CHAR(1),
    date_naissance DATETIME NOT NULL,
    nom VARCHAR(30),
    commentaires TEXT, -- better for me at the end 
    PRIMARY KEY (id)                 
)
ENGINE=InnoDB;

-- ajout de clee primaire
ALTER TABLE nom_table
ADD [CONSTRAINT [symbole_contrainte]] PRIMARY KEY (colonne_pk1 [, colonne_pk2, ...]);

-- delete remove clee primaire
ALTER TABLE nom_table
DROP PRIMARY KEY
```
note sur les clee etrangere
1. elles peuvent etre composites
2. quand je les cree, un index est creer sur elles en meme temps
3. la colomne qui sert de reference doit posseder un index
4. les deux colomne ou groupe doivent posseder les meme types
5. ca ne marche pas avec le moteur MyISAM

```sql
    CREATE TABLE [IF NOT EXISTS] Nom_table (
    colonne1 description_colonne1,
    [colonne2 description_colonne2,
    colonne3 description_colonne3,
    ...,]
    [ [CONSTRAINT [symbole_contrainte]]  FOREIGN KEY (colonne(s)_clé_étrangère) REFERENCES table_référence (colonne(s)_référence)]
)
[ENGINE=moteur];


CREATE TABLE Commande (
    numero INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    client INT UNSIGNED NOT NULL,
    produit VARCHAR(40),
    quantite SMALLINT DEFAULT 1,
    CONSTRAINT fk_client_numero          -- On donne un nom à notre clé
        FOREIGN KEY (client)             -- Colonne sur laquelle on crée la clé
        REFERENCES Client(numero)        -- Colonne de référence
)
ENGINE=InnoDB;                          -- MyISAM interdit, je le rappelle encore une fois !

-- ajouter ----

ALTER TABLE Commande
ADD CONSTRAINT fk_client_numero FOREIGN KEY (client) REFERENCES Client(numero);


-- deleter ---

ALTER TABLE nom_table
DROP FOREIGN KEY symbole_contrainte


```

```sql

-- j'ajoute a ma table animal la colomne pour linker avec race
ALTER TABLE Animal ADD COLUMN race_id SMALLINT UNSIGNED; -- même type que la colonne id de Espece

-- j'ajoute aussi les deux colomne pour faire link avec le pere et la mere 
ALTER TABLE Animal ADD COLUMN pere_id SMALLINT UNSIGNED; 
ALTER TABLE Animal ADD COLUMN mere_id SMALLINT UNSIGNED; 

CREATE TABLE Race (
    id SMALLINT UNSIGNED AUTO_INCREMENT,
    nom_courant VARCHAR(40) NOT NULL,
    nom_latin VARCHAR(40) NOT NULL UNIQUE,
    description TEXT,
    PRIMARY KEY(id)
)
ENGINE=InnoDB;

-- ajout des contrainte entre les deux tables, c'est la table animal qui porte la containte, car elle link avec l'autre table qui a les containte de clee unique. 
ALTER TABLE animal
ADD CONSTRAINT fk_race_id FOREIGN KEY (race_id) REFERENCES race(id);

-- ajout de contraint sur elle meme
ALTER TABLE animal
ADD CONSTRAINT fk_pere_id FOREIGN KEY (pere_id) REFERENCES animal(id);

ALTER TABLE animal
ADD CONSTRAINT fk_mere_id FOREIGN KEY (mere_id) REFERENCES animal(id);

ALTER TABLE animal
DROP FOREIGN KEY fk_race_id;

```
###conclusion
1. une clee primaire permet d'identifier chaque line de maniere unique
2. chaque table en a besion d'une 
3. les clee etrangere permettent de relier les tables entre elles assurant securiter et coerance

# les jointures
elles permettent de joindre plusiere table 
```sql
SELECT Espece.description 
FROM Espece 
INNER JOIN Animal 
    ON Espece.id = Animal.espece_id 
WHERE Animal.nom = 'Cartouche';
```
les jointures genere une nouvelle table avec les deux table precedements creer. \

les alias 
```sql
SELECT 5+3 AS Chiots_Cartouche;

-- OU, sans utiliser AS

SELECT 5+3 Chiots_Cartouche;
```
shema type avec les jointures interne
```sql
SELECT *                                   -- comme d'habitude, vous sélectionnez les colonnes que vous voulez
FROM nom_table1   
[INNER] JOIN nom_table2                    -- INNER explicite le fait qu'il s'agit d'une jointure interne, mais c'est facultatif
    ON colonne_table1 = colonne_table2     -- sur quelles colonnes se fait la jointure
                                           -- vous pouvez mettre colonne_table2 = colonne_table1, l'ordre n'a pas d'importance

[WHERE ...]                               
[ORDER BY ...]                            -- les clauses habituelles sont bien sûr utilisables !
[LIMIT ...]

-- mais je ne suis pas limiter a une seul colome, table je peux aussi faire : 


SELECT *
FROM table1
INNER JOIN table2
   ON table1.colonneA = table2.colonneJ
      AND table1.colonneT = table2.colonneX
      [AND ...];
```
Exemple : sélection du nom des animaux commençant par "Ch", \
ainsi que de l'id et la description de leur espèce.
```sql
SELECT Espece.id,                   -- ici, pas le choix, il faut préciser
       Espece.description,          -- ici, on pourrait mettre juste description
       Animal.nom                   -- idem, la précision n'est pas obligatoire. C'est cependant plus clair puisque les espèces ont un nom aussi
FROM Espece   
INNER JOIN Animal
     ON Espece.id = Animal.espece_id
WHERE Animal.nom LIKE 'Ch%';


--- la meme recherche avec des alias : 
SELECT e.id,                  
       e.description,          
       a.nom                   
FROM Espece AS e          -- On donne l'alias "e" à Espece
INNER JOIN Animal AS a    -- et l'alias "a" à Animal.
     ON e.id = a.espece_id
WHERE a.nom LIKE 'Ch%';


--- mais si je prends les alias pour faire du good job et renomer les colomne correctement 
SELECT Espece.id AS id_espece,                  
       Espece.description AS description_espece,          
       Animal.nom AS nom_bestiole                   
FROM Espece   
INNER JOIN Animal
     ON Espece.id = Animal.espece_id
WHERE Animal.nom LIKE 'Ch%';

```
le pb avec ce genre de jointure est que ca ne marche pas si les deux col on des data en commun sinon ca ne marchera pas  \
car les deux tables n'ont rien en commun, ca ne prendra pas les param avec les colomn qui font null \
mais pas les colomne qui les font matcher comme elle ne peuvent pas etre null. // \
je peux faire les jointures par la droite ou par la gauche 


```sql
-- permet de selectionner les animaux qui on des race set a null, ce qui ne marcherai pas en tant normal
SELECT Animal.nom AS nom_animal, Race.nom AS race
FROM Animal                                                -- Table de gauche
RIGHT JOIN Race                                            -- Table de droite
    ON Animal.race_id = Race.id
WHERE Race.espece_id = 2
ORDER BY Race.nom, Animal.nom;

-- OU

SELECT Animal.nom AS nom_animal, Race.nom AS race
FROM Animal                                              -- Table de gauche
RIGHT OUTER JOIN Race                                    -- Table de droite
    ON Animal.race_id = Race.id
WHERE Race.espece_id = 2
ORDER BY Race.nom, Animal.nom;

```

quand les deux tables ont les memes colone je peux les linker en jointure 
```sql
SELECT *
FROM table1
[INNER | LEFT | RIGHT] JOIN table2 USING (colonneJ);  -- colonneJ est présente dans les deux tables

-- équivalent à 

SELECT *
FROM table1
[INNER | LEFT | RIGHT] JOIN table2 ON table1.colonneJ = table2.colonneJ;

-- si tout les colomnes que je veux linker on les meme nom entre deux table je peux faire un natural joint 
-- je fais ici la jointure entre ces deux table a et b
SELECT * 
FROM table1
NATURAL JOIN table3;

-- EST ÉQUIVALENT À

SELECT *
FROM table1
INNER JOIN table3
    ON table1.A = table3.A AND table1.C = table3.C;

```


```sql
-- exo
SELECT race.nom
FROM race
where race.nom LIKE '%berger%'


SELECT animal.nom, animal.date_naissance, race.nom, race.description
FROM animal
LEFT JOIN race 
    ON animal.race_id = race.id
WHERE (race.description NOT LIKE '%pelage%' AND race.description NOT LIKE '%poil%' AND race.description NOT LIKE '%robe%')
or race_id IS NULL
    ;


SELECT animal.nom, race.nom as race_animal, espece.nom_latin as espece, animal.sexe
FROM animal
LEFT JOIN race 
    ON animal.race_id = race.id
INNER JOIN espece 
    ON animal.espece_id = espece.id
-- je peux faire : 
WHERE espece.nom_courant IN ('Perroquet amazone', '%chat%') 


-- a la place de 
where 
    espece.nom_courant LIKE 'Perroquet amazone'
 OR 
    espece.nom_courant LIKE '%chat%'
ORDER by espece.nom_latin, race_animal
;




SELECT animal.nom,  animal.date_naissance, race.nom
FROM animal
INNER JOIN race 
    ON animal.race_id = race.id
INNER JOIN espece
    ON animal.espece_id = espece.id
where 
        date_naissance > 01072016 
    AND 
        espece.nom_courant LIKE 'chien'
    AND 
        Animal.sexe = 'F';
;

SELECT animal.nom, animal.pere_id as papa, animal.mere_id as maman
FROM animal
INNER JOIN animal as Pere
    ON animal.pere_id = Pere.id
INNER JOIN animal as Maman
    ON animal.mere_id = Maman.id
INNER JOIN espece
    ON animal.espece_id = espece.id
where 



``` 


bien se rappeler que je peux faire des autojointure et que les autojointure c'est super bien! \
les jointure c'est super \
les jointure interne prennent les data qui ne sont pas null \
les externe toute les data en fonction du sens de lecture; \
on peut joindre une table a elle meme , ca s'appelle l'autojointure

# les sous requetes
























































































































