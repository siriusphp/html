<?php

namespace Sirius\Html;

/**
 * Extended class for building HTML elements.
 *
 * It offers an interface similar to jQuery's DOM handling functionality
 * (besides the Input's class functionality)
 * - before(): add something before the element
 * - after(): add something after the element;
 */
class Tag implements \Stringable
{
    /**
     * Items (strings) to be added before the element
     *
     * @var array<int,string|Tag>
     */
    protected array $before = [];

    /**
     * Items (strings) to be added after the element
     *
     * @var array<int,string|Tag>
     */
    protected array $after = [];

    protected string $tag = 'div';

    protected bool $isSelfClosing = false;

    /**
     * @var array<string,mixed>
     */
    protected array $props = [];

    /**
     * @var array<int,string|Tag|\Stringable>
     */
    protected array $content = [];

    protected ?Tag $parent = null;

    protected ?Builder $builder = null;

    /**
     * Factory method.
     * If $tag ends in '/' the tag will be considered 'self-closing'
     *
     * @example ExtendedTag::factory('hr/', null, ['class' => 'separator']);
     *          ExtendedTag::factory('div', 'This is my content', ['class' => 'container']);
     *
     * @param string $tag
     * @param array<string,mixed> $props
     * @param string|array<int,string|Tag|\Stringable> $content
     */
    public static function create($tag, array $props = [], mixed $content = null, Builder $builder = null): Tag
    {
        $widget = new Tag($props, $content, $builder);
        if (str_ends_with($tag, '/')) {
            $widget->tag           = substr($tag, 0, -1);
            $widget->isSelfClosing = true;
        } else {
            $widget->tag           = $tag;
            $widget->isSelfClosing = false;
        }
        $widget->setContent($content);

        return $widget;
    }

    /**
     * @param array<string,mixed> $props
     * @param string|array<int,string|Tag|\Stringable> $content
     */
    public function __construct(array $props = [], mixed $content = null, Builder $builder = null)
    {
        $this->builder = $builder;
        if ($props !== null) {
            $this->setProps($props);
        }
        if ($content !== null) {
            $this->setContent($content);
        }
    }

    /**
     * @param array<string,mixed> $props
     */
    public function setProps(array $props): self
    {
        if (!is_array($props)) {
            return $this;
        }
        foreach ($props as $name => $value) {
            $this->set($name, $value);
        }

        return $this;
    }

    public function set(string $name, mixed $value = null): static
    {
        $name = $this->cleanAttributeName($name);
        if ($value === null && isset($this->props[$name])) {
            unset($this->props[$name]);
        } elseif ($value !== null) {
            $this->props[(string)$name] = $value;
        }

        return $this;
    }

    protected function cleanAttributeName(string $name): string
    {
        // private attributes are allowed to have any form
        if (str_starts_with($name, '_')) {
            return $name;
        }

        return (string) preg_replace('/[^a-zA-Z0-9-]+/', '', $name);
    }

    /**
     * @param list<string> $list
     *
     * @return array<string,mixed>
     */
    public function getProps(array $list = []): array
    {
        if (!empty($list)) {
            $result = [];
            foreach ($list as $key) {
                $result[$key] = $this->get($key);
            }

            return $result;
        }

        return $this->props;
    }

    /**
     * Returns one of HTML element's properties
     */
    public function get(string $name): mixed
    {
        $name = $this->cleanAttributeName($name);

        return $this->props[$name] ?? null;
    }

    public function addClass(string $class): self
    {
        if (!$this->hasClass($class)) {
            // @phpstan-ignore-next-line
            $this->set('class', trim((string) $this->get('class') . ' ' . $class));
        }

        return $this;
    }

    public function removeClass(string $class): self
    {
        $classes = $this->get('class');
        if ($classes) {
            // @phpstan-ignore-next-line
            $classes = trim((string) preg_replace('/(^| ){1}' . $class . '( |$){1}/i', ' ', (string) $classes));
            $this->set('class', $classes);
        }

        return $this;
    }

    public function toggleClass(string $class): self
    {
        if ($this->hasClass($class)) {
            return $this->removeClass($class);
        }

        return $this->addClass($class);
    }

    public function hasClass(string $class): bool
    {
        $classes = $this->get('class');

        // @phpstan-ignore-next-line
        return $classes && ((bool) preg_match('/(^| ){1}' . $class . '( |$){1}/i', (string) $classes));
    }

    public function setContent(mixed $content): static
    {
        if (!$content) {
            return $this;
        }
        if (!is_array($content)) {
            $content = [$content];
        }
        $this->clearContent();
        foreach ($content as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * Get the content/children
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Empties the content of the tag
     *
     * @return \Sirius\Html\Tag
     */
    public function clearContent()
    {
        $this->content = [];

        return $this;
    }

    /**
     * @param string|Tag|array<int,mixed>$tagTextOrArray
     */
    protected function addChild($tagTextOrArray): self
    {
        // a list of arguments to be passed to builder->make()
        if (is_array($tagTextOrArray) && !empty($tagTextOrArray)) {
            if (!isset($this->builder)) {
                throw new \InvalidArgumentException(sprintf('Builder not attached to tag `%s`', $this->tag));
            }

            // @phpstan-ignore-next-line
            $tagName        = (string) $tagTextOrArray[0];
            $props          = (array) ($tagTextOrArray[1] ?? [ ]);
            $content        = (array) ($tagTextOrArray[2] ?? [ ]);
            // @phpstan-ignore-next-line
            $tagTextOrArray = $this->builder->make($tagName, $props, $content);
        }

        /** @var string|\Stringable|Tag $tagTextOrArray */
        $this->content[] = $tagTextOrArray;

        return $this;
    }

    /**
     * Return the attributes as a string for HTML output
     * example: title="Click here to delete" class="remove"
     */
    protected function getAttributesString(): string
    {
        $result = '';
        ksort($this->props);
        foreach ($this->props as $k => $v) {
            if (!str_starts_with($k, '_')) {
                if ($v === true) {
                    $result .= $k . ' ';
                } else {
                    $result .= $k . '="' . $this->escapeAttr($v) . '" ';
                }
            }
        }
        if ($result) {
            $result = ' ' . trim($result);
        }

        return $result;
    }

    protected function escapeAttr(mixed $attr): string
    {
        // @phpstan-ignore-next-line
        $attr = (string) $attr;

        if (0 === strlen($attr)) {
            return '';
        }

        // Don't bother if there are no specialchars - saves some processing
        if (!preg_match('/[&<>"\']/', $attr)) {
            return $attr;
        }

        return htmlspecialchars($attr, ENT_COMPAT);
    }

    /**
     * Render the element
     *
     * @return string
     */
    public function render()
    {
        if ($this->isSelfClosing) {
            $element = "<{$this->tag}".$this->getAttributesString()." />";
        } else {
            $element = "<{$this->tag}".$this->getAttributesString().">".$this->getInnerHtml()."</{$this->tag}>";
        }
        $before = !empty($this->before) ? implode(PHP_EOL, $this->before) : '';
        $after  = !empty($this->after) ? implode(PHP_EOL, $this->after) : '';

        return $before . $element . $after;
    }

    /**
     * Return the innerHTML content of the tag
     *
     * @return string
     */
    public function getInnerHtml()
    {
        return implode(PHP_EOL, $this->content);
    }

    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Add a string or a stringifiable object immediately before the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function before($stringOrObject)
    {
        array_unshift($this->before, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object immediately after the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function after($stringOrObject)
    {
        array_push($this->after, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object immediately as the first child of the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function prepend($stringOrObject)
    {
        array_unshift($this->content, $stringOrObject);

        return $this;
    }

    /**
     * Add a string or a stringifiable object as the last child the element
     *
     * @param string|object $stringOrObject
     *
     * @return Tag
     */
    public function append($stringOrObject)
    {
        array_push($this->content, $stringOrObject);

        return $this;
    }

    /**
     * Add something before and after the element.
     * Proxy for calling before() and after() simoultaneously
     *
     * @param string|object $before
     * @param string|object $after
     *
     * @return Tag
     */
    public function wrap($before, $after)
    {
        return $this->before($before)->after($after);
    }
}
