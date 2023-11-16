<?php

namespace Sirius\Html;

class Builder
{
    /**
     * tag-to-class name mappings
     *
     * @var array<string,class-string|callable():Tag|\Closure>
     */
    protected array $tagFactories = [
        'button'      => \Sirius\Html\Tag\Button::class,
        'checkbox'    => \Sirius\Html\Tag\Checkbox::class,
        'file'        => \Sirius\Html\Tag\File::class,
        'hidden'      => \Sirius\Html\Tag\Hidden::class,
        'img'         => \Sirius\Html\Tag\Img::class,
        'multiselect' => \Sirius\Html\Tag\MultiSelect::class,
        'password'    => \Sirius\Html\Tag\Password::class,
        'radio'       => \Sirius\Html\Tag\Radio::class,
        'select'      => \Sirius\Html\Tag\Select::class,
        'text'        => \Sirius\Html\Tag\Text::class,
        'textarea'    => \Sirius\Html\Tag\Textarea::class
    ];

    /**
     * @param array<string,mixed> $options
     */
    public function __construct(
        /**
         * A list of configuration parameters that can be used
         * by tags, decorators etc when rendering
         */
        protected array $options = []
    ) {
    }

    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    public function getOption(string $name): mixed
    {
        return $this->options[$name] ?? null;
    }

    /**
     * Clones the instance and applies new set of options
     *
     * @param array<string,mixed> $options
     */
    public function with(array $options): static
    {
        $clone = clone $this;
        foreach ($options as $name => $value) {
            $clone->setOption($name, $value);
        }

        return $clone;
    }

    /**
     * @param class-string|callable():Tag|\Closure():Tag $classOrCallback
     */
    public function registerTag(string $name, mixed $classOrCallback): self
    {
        $this->tagFactories[$name] = $classOrCallback;

        return $this;
    }

    /**
     * Make an HTML tag with a specific tag name (div, p, section etc)
     *
     * @param string $tag
     * @param array<string,mixed> $props
     * @param array<int, string|\Stringable>|string|null $content
     *
     * @throws \InvalidArgumentException
     */
    public function make($tag, array $props = [], mixed $content = null): Tag
    {
        if ( ! isset($this->tagFactories[$tag])) {
            return Tag::create($tag, $props, $content, $this);
        }

        $factory = $this->tagFactories[$tag];

        if (is_callable($factory)) {
            /* @var $tag Tag */
            $tag = call_user_func($factory, $props, $content, $this);
        } elseif (is_string($factory) && class_exists($factory)) {
            /* @var $tag Tag */
            $tag = new $factory($props, $content, $this);
        }

        if ( ! $tag || ! $tag instanceof Tag) {
            throw  new \InvalidArgumentException(sprintf(
                'The constructor for the `%s` tag did not generate a Tag object',
                $tag
            ));
        }

        return $tag;

    }

    /**
     * Magic method for creating HTML tags
     *
     * @param string $method
     * @param array<int,mixed> $args
     *
     * @return Tag
     * @example
     * $builder->h1(null, 'Heading 1'); // <h1>Heading 1</h1>
     * $builder->article(['class' => 'post-body'], 'Article body');
     * // outputs: '<article class='post-body'>Article body</article>
     *
     * $builder->someTag(); // <some-tag></some-tag>
     *
     */
    public function __call(string $method, array $args)
    {
        $method = preg_replace('/([A-Z]+)/', '-\1', $method);
        $method = strtolower((string) $method);
        if ( ! isset($args[0])) {
            $args[0] = [];
        }
        if ( ! isset($args[1])) {
            $args[1] = [];
        }
        if ( ! isset($args[2])) {
            $args[2] = null;
        }

        // @phpstan-ignore-next-line
        return $this->make($method, (array) $args[0], (array) $args[1]);
    }
}
