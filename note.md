
#intro --> la structure du projets
pour eviter les failles de securiter avoir tout les fichiers non disponible, \
je fait une dossier public, qui contiendra la class index qui redirigera tout \
et sera empechera de revenir en arriere ce dossiser sera la racine de mon app, \
et empechera de revenir en arriere. \
Je definirai pour ca 

# les commentaires 
comment faire une belle documentation avec phpstrom :

#exemple d'heritage
exemple avec le cas du formulaire
d'abort le form, ensuite le form boostrap

#l'auto-loding
je peux faire le gros bourin et faire la function `__autoload` qui va tout charger comme un bourain, mais ca c'est null ! \
je fais une class qui va faire l'autoloding, \
je la renseign avec un tab --> spl_autoloading_register("name class" , "name static method") \
elle va ensuite automatique charger toute les class !! 
regarder directement la class Autoloader pour avoir les procedure
tout enfermer dans des class permet d'avoir du code qui ne rentrera pas en conflit avec les codes des autres gens

#les namespaces
permet d'avoir des class avec le meme nom \
bien faire attention au chemin et a mon loader pour faire les choses correctement \
le namespace ce met au debut du fichier \
faire attention a l'arborescence de mes fichiers qui doit respecter le namespace \
je peux charger plusieur namespace dans mes fichiers \
--> permet de connaitre des qu'on lit le code les class que je vais utiliser \
avec mon autoloader, si je veux directement get les fonction systeme, je fais --> `\` devant (comme pour use  _ la fonction date par exemple) \
voir dans le code un exemple de protection de l'autoload, qui se lance seulement si je suis dans le bon namespace


-------------------------------------------------------------------------------------------------------------------
#le blog [ 11 ] la structure
le fichier index.php va fournir un litle routing pour l'app   

#le pdo 
il permet de se connecter a la base de donner proprement






exo 
==> faire un trait qui me permet de faire des magnifique form :)
==>