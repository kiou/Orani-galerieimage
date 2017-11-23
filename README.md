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
{% set menuGalerieimage = ['admin_galerieimage_manager', 'admin_galerieimage_ajouter', 'admin_galerieimage_modifier','admin_galerieimage_categorie_manager', 'admin_galerieimage_categorie_modifier', 'admin_galerieimage_image_manager','admin_galerieimage_image_ajouter','admin_galerieimage_image_modifier'] %}

<a href="#" data-nav="galerieimage-menu" class="menuNav {{ getCurrentMenu(menuGalerieimage) }}"> <i class="fa fa-picture-o"></i> Galeries d'images <i class="fa fa-angle-right"></i></a>
<ul id="galerieimage-menu" class="{{ getCurrentMenu(menuGalerieimage) }}">
    <li class="{{ getCurrentMenu(['admin_galerieimage_ajouter']) }}"><a href="{{ path('admin_galerieimage_ajouter')}}">Ajouter une galerie d'image</a></li>
    <li class="{{ getCurrentMenu(['admin_galerieimage_manager']) }}"><a href="{{ path('admin_galerieimage_manager')}}">Gestion des galeries d'images</a></li>
    <li class="{{ getCurrentMenu(['admin_galerieimage_categorie_manager']) }}"><a href="{{ path('admin_galerieimage_categorie_manager')}}">Gestion des catégories</a></li>
</ul>
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