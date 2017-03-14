ekyna-learn/sf-todo
=======

### Créer une TodoList avec Symfony.

1. Créer une entité __Todo__ :
    
    * content [text]
    * trashed [boolean]
    * date [datetime]

2. Développer les routes et les actions pour le contrôleur __TodoController__ : 

    | Url | Action |
    | --- | ---- |
    | / | Liste des Todos (dates décroissantes) |
    | /add | Ajouter un Todo |
    | /[id]/edit | Modifier le Todo [id] |
    | /[id]/trash | Archiver le Todo [id] |

4. Développer les pages suivantes :

    | Url | Action |
    | --- | ---- |
    | /trashed | Liste des Todos archivés (dates décroissantes) |
    | /[id]/restore | Restaurer le Todo archivé  [id] |
    | /[id]/remove | Supprimer le Todo archivé [id] |
