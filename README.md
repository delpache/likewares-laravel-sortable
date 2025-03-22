# Sortable behavior package for Laravel

Ce package vous permet d'ajouter un comportement de tri aux `models` et aux `vues`. Il est livré avec un trait dans lequel vous pouvez définir les champs triables et une directive blade pour générer automatiquement les en-têtes de table.

## Démarrer

### 1. Installation

Exécutez la commande suivante :

```bash
composer require likewares/laravel-sortable
```

### 2. Publication

Publication de la configuration

```bash
php artisan vendor:publish --tag=sortable
```

### 3. Configuration

Vous pouvez modifier les paramètres de tri des colonnes de votre application à partir du fichier `config/sortable.php`.

## Utilisation

Tout ce que vous avez à faire est d'utiliser le trait `Sortable` dans votre modèle et de définir les champs `$sortable`.

```php
use Likewares\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sortable;
    ...

    public $sortable = [
        'id',
        'title',
        'author',
        'created_at',
    ];
    ...
}
```

Si vous ne définissez pas le tableau `$sortable`, la fonction `Scheme::hasColumn()` est utilisée et lance une requête supplémentaire à la base de données.

### Scope

Le trait ajoute une portée `sortable` au modèle afin que vous puissiez l'utiliser juste avant `paginate` :

```php
public function index()
{
    $posts = Post::query()->sortable()->paginate(10);

    return view('posts.index')->with(['posts' => $posts]);
}
```

Vous pouvez également définir un champ de tri par défaut qui sera appliqué lorsque l'URL est vide.

```php
$posts = $post->sortable(['author'])->paginate(10); // $post->orderBy('posts.author', 'asc')

$posts = $post->sortable(['title'])->paginate(10); // $post->orderBy('posts.title', 'asc')

$posts = $post->sortable(['title' => 'desc'])->paginate(10); // $post->orderBy('posts.title', 'desc')
```

### Blade Directive

Il existe également une directive `blade` qui vous permet de créer des liens triables dans vos vues :

```blade
@sortablelink('title', trans('general.title'), ['parameter' => 'smile'],  ['rel' => 'nofollow'])
```

Le *premier* paramètre est la colonne dans la base de données. Le *deuxième* est affiché à l'intérieur de la balise d'ancrage. Le *troisième* est un `array()`, et il définit la chaîne de requête par défaut (GET). Le *quatrième* est également un `array()` pour les attributs supplémentaires de la balise d'ancrage. Vous pouvez utiliser une URL personnalisée comme attribut 'href' dans le quatrième paramètre, qui ajoutera la chaîne de requête.

Seul le premier paramètre est requis.

Exemples:

```blade
@sortablelink('title')
@sortablelink('title', trans('general.title'))
@sortablelink('title', trans('general.title'), ['filter' => 'active, visible'])
@sortablelink('title', trans('general.title'), ['filter' => 'active, visible'], ['class' => 'btn btn-success', 'rel' => 'nofollow', 'href' => route('posts.index')])
```

#### Jeux d'icônes

Vous pouvez utiliser n'importe quel jeu d'icônes. Il suffit de changer le `icons.wrapper` du fichier de configuration en conséquence. Par défaut, il utilise Font Awesome.

### Blade Component

Comme pour la directive, il existe également un composant `blade` qui vous permet de créer des liens triables dans vos vues :

```html
<x-sortablelink column="title" title="{{ trans('general.title') }}" :query="['parameter' => 'smile']"  :arguments="['rel' => 'nofollow']" />
```

### Sorting Relationships

Le package supporte les tris relationnels `HasOne` et `BelongsTo` :

```php
class Post extends Model
{
    use Sortable;
    ...

    protected $fillable = [
        'title',
        'author_id',
        'body',
    ];

    public $sortable = [
        'id',
        'title',
        'author',
        'created_at',
        'updated_at',
    ];

    /**
    * Get the author associated with the post.
    */
    public function author()
    {
        return $this->hasOne(\App\Models\Author::class);
    }
    ...
}
```

Et vous pouvez utiliser la relation dans les vues :

```blade
// resources/views/posts/index.blade.php

@sortablelink('title', trans('general.title'))
@sortablelink('author.name', trans('general.author'))
```

> **Note**: Dans le cas d'un modèle autoréférencé (comme les commentaires, les catégories, etc.), la table parent sera aliasée avec la chaîne `parent_`.

### Relation avancée

Vous pouvez également étendre la fonctionnalité de tri des relations en créant une fonction avec le suffixe `Sortable`. Vous êtes alors libre d'écrire vos propres requêtes et d'appliquer `orderBy()` manuellement :

```php
class User extends Model
{
    use Sortable;
    ...

    public $sortable = [
        'name',
        'address',
    ];

    public function addressSortable($query, $direction)
    {
        return $query->join('user_details', 'users.id', '=', 'user_details.user_id')
                    ->orderBy('address', $direction)
                    ->select('users.*');
    }
    ...
```

L'utilisation dans `controller` et `view` reste la même.

### Alias

Vous pouvez déclarer le tableau `$sortableAs` dans votre modèle et l'utiliser comme alias (contourner la vérification de l'existence des colonnes), et ignorer le préfixe avec table :

```php
public $sortableAs = [
    'nick_name',
];
```

Dans le controller

```php
$users = $user->select(['name as nick_name'])->sortable(['nick_name'])->paginate(10);
```

Dans la view

```blade
@sortablelink('nick_name', 'nick')
```

C'est très utile lorsque vous souhaitez trier les résultats à l'aide de [`withCount()`](https://laravel.com/docs/eloquent-relationships#counting-related-models).

## License

La licence MIT (MIT). Veuillez consulter [LICENSE](LICENSE.md) pour plus d'informations.
