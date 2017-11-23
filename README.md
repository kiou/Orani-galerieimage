## Administration
* Ajouter une galerie
* Gestion des galeries
* Publier une galerie
* Supprimer une galerie
* Poid d'une galerie
* Modifier une galerie
* Ajouter une image
* Gestion des images
* Publier une image
* Modifier une image
* Poid des images

## Client
* Liste des galeries
* Afficher une galerie
* Template la liste des derniéres galeries

## Dépendances
* RefrencementBundle
* GlobalBundle
* Tinymce
* Filemanager
* SweetAlert
* Lightbox 2

## Installation
### Menu
```twig
ICI
```

### Fichier
* app/AppKernel.php
```php
new GalerieImageBundle\GalerieImageBundle(),
```
* app/config.yml
```yml
- { resource: "@GalerieImageBundle/Resources/config/services.yml" }
```
* app/routing.yml
```yml
galerieimage
    resource: "@GalerieImageBundle/Resources/config/routing.yml"
    prefix:   /
```
## Client
* Ajouter le dossier web/img/galerie/tmp
* Ajouter le dossier web/img/galerie/minitaure
* Design disponible dans le dossier Install
* JS disponible dans le dossier Install 