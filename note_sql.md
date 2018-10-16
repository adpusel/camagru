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















































































































