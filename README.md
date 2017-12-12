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
* Trier par catégorie
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
{% set menuDiaporama = ['admin_diaporama_manager', 'admin_diaporama_ajouter', 'admin_diaporama_modifier','admin_diaporama_categorie_manager', 'admin_diaporama_categorie_modifier', 'admin_diaporama_image_manager','admin_diaporama_image_ajouter','admin_diaporama_image_modifier'] %}

<a href="#" data-nav="diaporama-menu" class="menuNav {{ getCurrentMenu(menuDiaporama) }}"> <i class="fa fa-picture-o"></i> Galeries d'images <i class="fa fa-angle-right"></i></a>
<ul class="diaporama-menu {{ getCurrentMenu(menuDiaporama) }}">
    <li class="{{ getCurrentMenu(['admin_diaporama_ajouter']) }}"><a href="{{ path('admin_diaporama_ajouter')}}">Ajouter une galerie d'image</a></li>
    <li class="{{ getCurrentMenu(['admin_diaporama_manager']) }}"><a href="{{ path('admin_diaporama_manager')}}">Gestion des galeries d'images</a></li>
    <li class="{{ getCurrentMenu(['admin_diaporama_categorie_manager']) }}"><a href="{{ path('admin_diaporama_categorie_manager')}}">Gestion des catégories</a></li>
</ul>
```

### Fichier
* app/AppKernel.php
```php
new DiaporamaBundle\DiaporamaBundle(),
```
* app/config.yml
```yml
- { resource: "@DiaporamaBundle/Resources/config/services.yml" }
```
* app/routing.yml
```yml
diaporama:
    resource: "@DiaporamaBundle/Resources/config/routing.yml"
    prefix:   /
```
## Client
* Ajouter le dossier web/img/galerie/tmp
* Ajouter le dossier web/img/galerie/minitaure
* Design disponible dans le dossier Install
* JS disponible dans le dossier Install 