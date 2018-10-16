# intro

composer est un gestionaire de dependance, et il gere l'autoload

1. le telecharger avec le lien du site
2. je build le composer.json
    ```bash
           php composer.phar init
    ```
5. les packages se trouvent sur le site et 
6 --> faire un test a la maison pour voir si ca marche :)

7. je declare autoload dans le composer comme dans le ficher \
    prochaine video ==> pk psr-4 ?
    je link le namespace App avec le dossier app;
```json
    "autoload": {
        "psr-4": {
            "App\\": "App"
        }
    }
```
8. jupdate composer pour refaire l'autoloading
9. comment ca marche \
```php 
// composer va aller dans le dir app et va faire 
// transformer le namespace en ce qu'il a besoin
// ==> /app/helper/form.php ==> plus besoin de require

require "app/helper/form";
echo \app\helper\Form::input();

```