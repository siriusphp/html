---
title: Custom HTML tags
---

# Custom HTML tags

Here's an example of a custom HTML tag that is supposed to render a blog post

```php
namespace MyApp\Html\Tag;

class BlogPost extends Sirius\Html\Tag {

    // the actual tag used to render
    protected $tag = 'article';

    function __construct($props = array(), $content, Sirius\Html\Builder $builder) {
        parent::__construct($props, $content, $builder);
        // here you can add default attributes, change the content etc
        $this->addClass('post');
        $this->addClass('post-' . $this->get('_post')->ID;
    }

    function render() {
        $h = $this->builder;
        $post = $this->get('_post');
        $this->setContent([
            $h->header([], $h->h1(["class" => 'title'], $post->title)),
            $h->section(["class" => "main"], $post->content),
            $h->blogPostGallery(['_images' => $post->images]) //another custom tag
        ]);
    }
}
```

Once you created the class for you custom tag you need to register it to the builder

```php
$h->registerTag('blog-post-gallery', 'MyApp\Html\Tag\BlogPostGallery');
$h->registerTag('blog-post', 'MyApp\Html\Tag\BlogPost');
```