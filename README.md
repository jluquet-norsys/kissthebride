## Prérequis

* Avoir Docker et Git d'installé sur son poste

## Installation

* Cloner le repertoire :

``` 
git clone https://github.com/jluquet-norsys/kissthebride.git
```

* Se placer dans le repertoire du projet et lancer les commandes :
``` 
  docker compose pull --include-deps
  docker compose up -d
``` 

* Pour vérifier que tout a bien fonctionné, la page suivante doit bien s'afficher : http://localhost/docs/

* Se connecter sur le container php
```
  docker compose exec -it php sh
  ```
et lancer les commandes :
``` 
  bin/console doctrine:migrations:migrate 
  bin/console doctrine:fixtures:load
``` 

* Toutes les routes sont visibles et testables ici : http://localhost/docs/
  * Un exemple de payload pour la création d'une note de frais en POST :

`POST /expense_accounts`

```json 
{
    "expenseDate": "2023-06-19",
    "employee": "/employees/1",
    "company": "/companies/1",
    "amount": 105.32,
    "type": "Toll"
}
```

* Il est possible de lancer les tests unitaires (seule la route de POST d'une nouvelle note est testée) avec la commande :
```
bin/phpunit 
```

* Il est possible d'ajouter un système d'authentification par token en suivant cette documentation : https://api-platform.com/docs/core/jwt/


