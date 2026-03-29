# Timber 2.x Reference

> Developed by Agência Upgrade — https://agenciaupgrade.com.br

Timber is a bridge between WordPress PHP and Twig templating. It wraps WordPress objects (posts, terms, menus) into PHP objects that are Twig-friendly.

## Key Concepts

### Context

Data passed to Twig templates. Set in `Site.php`:

```php
public function addToContext($context)
{
    $context['menu'] = Timber::get_menu('primary_navigation');
    $context['posts'] = Timber::get_posts();
    return $context;
}
```

Access in Twig:
```twig
{% for post in posts %}
  <h2>{{ post.title }}</h2>
{% endfor %}
```

### Post Objects

WordPress `WP_Post` → Timber `Timber\Post`

Properties:
```twig
{{ post.title }}
{{ post.content }}           {# Post body (filtered, has_more check) #}
{{ post.excerpt }}          {# Post excerpt (auto-generated if missing) #}
{{ post.link }}             {# Permalink #}
{{ post.date|date('Y-m-d') }} {# Publication date #}
{{ post.status }}
{{ post.type }}             {# post, page, portfolio, etc. #}
{{ post.image }}            {# Featured image (Timber\Image) #}
{{ post.thumbnail }}        {# Alias for image #}
```

### Images

WordPress attachments → Timber `Timber\Image`

```twig
<img src="{{ post.image.src }}" alt="{{ post.image.alt }}" />
<img src="{{ post.image.src|resize(400, 300) }}" /> {# Via Timber #}
```

### Menus

WordPress menu → Timber `Timber\Menu`

```twig
{% for item in menu.items %}
  <a href="{{ item.url }}" class="{% if item.current %}active{% endif %}">
    {{ item.title }}
  </a>
{% endfor %}
```

### Terms (Categories, Tags)

WordPress taxonomy terms → Timber `Timber\Term`

```twig
{% for term in post.terms('category') %}
  <a href="{{ term.link }}">{{ term.name }}</a>
{% endfor %}
```

### Custom Fields (Meta)

Get post meta:
```twig
{{ post.meta('my_meta_key') }}
```

## Common Patterns

### Loop Through Posts

```twig
{% for post in posts %}
  <article>
    <h2>{{ post.title }}</h2>
    <p>{{ post.excerpt }}</p>
    <a href="{{ post.link }}">Read more</a>
  </article>
{% endfor %}

{% if not posts %}
  <p>No posts found.</p>
{% endif %}
```

### Conditional Post Type

```twig
{% if post.type == 'portfolio' %}
  <div class="portfolio-item">
    {# Portfolio-specific markup #}
  </div>
{% elseif post.type == 'post' %}
  <article class="blog-post">
    {# Blog post markup #}
  </article>
{% endif %}
```

### Check for Featured Image

```twig
{% if post.image %}
  <img src="{{ post.image.src }}" alt="{{ post.image.alt }}" />
{% else %}
  <img src="/img/placeholder.jpg" alt="No image" />
{% endif %}
```

### Author Info

```twig
<p>By {{ post.author.name }}
  ({{ post.author.user_email }})
</p>
```

### Related Posts

```twig
{% set related = Timber.get_posts({
  'post__not_in': [post.id],
  'posts_per_page': 3,
  'tax_query': [{
    'taxonomy': 'category',
    'terms': post.terms('category')|map(attribute='id')|join(',')
  }]
}) %}

{% for post in related %}
  <a href="{{ post.link }}">{{ post.title }}</a>
{% endfor %}
```

### Pagination

```php
// In PHP template
$posts = Timber::get_posts([
    'posts_per_page' => 10,
    'paged' => get_query_var('paged') ?: 1,
]);

$context = Timber::context();
$context['posts'] = $posts;
$context['pagination'] = Timber::get_pagination();

Timber::render('templates/archive.twig', $context);
```

In Twig:
```twig
{% for post in posts %}
  {# Post markup #}
{% endfor %}

{% for page in pagination %}
  {% if page.current %}
    <span class="current">{{ page.number }}</span>
  {% else %}
    <a href="{{ page.link }}">{{ page.number }}</a>
  {% endif %}
{% endfor %}
```

## Query Examples

### Get Posts by Post Type

```php
$posts = Timber::get_posts([
    'post_type' => 'portfolio',
    'posts_per_page' => -1,
]);
```

### Get Posts by Category

```php
$posts = Timber::get_posts([
    'tax_query' => [{
        'taxonomy' => 'category',
        'terms' => 'business',
    }],
]);
```

### Get Posts by Meta

```php
$posts = Timber::get_posts([
    'meta_key' => 'featured',
    'meta_value' => '1',
]);
```

### Get Recent Posts (Exclude Current)

```php
$posts = Timber::get_posts([
    'post__not_in' => [get_the_ID()],
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
]);
```

### Search Results

```php
$context = Timber::context();
$context['posts'] = Timber::get_posts();
$context['search_query'] = get_search_query();

Timber::render('templates/search.twig', $context);
```

In Twig:
```twig
<h1>Search results for "{{ search_query }}"</h1>
{% for post in posts %}
  <h2>{{ post.title }}</h2>
{% endfor %}
```

## Built-in Filters

```twig
{{ text|truncate(50) }}      {# Truncate to 50 chars #}
{{ text|wordwrap(80) }}      {# Wrap at 80 chars #}
{{ html|striptags }}         {# Remove HTML tags #}
{{ date|date('F j, Y') }}    {# Format date #}
{{ 'hello world'|capitalize }}
```

## Custom Filters

Add in `Site.php`:

```php
public function addFiltersToTwig($filters)
{
    $filters['excerpt'] = new TwigFilter('excerpt', function ($text, $length = 55) {
        return wp_trim_words($text, $length);
    });
    return $filters;
}
```

Use in Twig:
```twig
{{ post.content|excerpt(30) }}
```

## Performance Tips

1. **Limit posts:** Use `posts_per_page` to avoid expensive queries
2. **Pre-fetch data in PHP:** Do complex queries in the template file, not Twig
3. **Cache expensive queries:** Use transients or Redis
4. **Avoid N+1 queries:** Pre-fetch related data before looping

Bad — query inside Twig loop (N+1):
```twig
{% for post in posts %}
  {% set related = Timber.get_posts({'post__not_in': [post.id]}) %}
{% endfor %}
```

Good — fetch once in PHP, pass to context:
```php
$context['related'] = Timber::get_posts([...]);
```

## Resources

- [Timber Docs](https://timber.github.io/docs/v2/)
- [Twig Reference](https://twig.symfony.com/doc/2.x/)
- [WordPress `get_posts()` Args](https://developer.wordpress.org/reference/functions/get_posts/)
