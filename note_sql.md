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
    CREATE USER 'student'@'localhost' IDENTIFIED BY 'mot_de_passe';
	GRANT ALL PRIVILEGES ON elevage.* TO 'student'@'localhost';

    CREATE USER 'student'  -- : cette commande crée l'utilisateur student.
    -- @'localhost'  : définit à partir d'où l'utilisateur peut se connecter. Dans notre cas, 'localhost', donc il devra être connecté à partir de cet ordinateur.

    IDENTIFIED BY 'mot_de_passe';  -- : définit le mot de passe de l'utilisateur.

    GRANT ALL PRIVILEGES  -- : cette commande permet d'attribuer tous les droits (c'est-à-dire insertions de données, sélections, 
    modifications, suppressions…).

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



































