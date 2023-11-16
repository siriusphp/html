<?php

namespace Sirius\Html\Tag;

class Select extends Input
{
    protected string $tag = 'select';

    protected bool $isSelfClosing = false;

    /**
     * Generates the string with the list of the <OPTIONS> elements
     */
    protected function getOptionsString(): string
    {
        $value   = $this->getValue();
        $options = '';
        if ($this->get('_first_option')) {
            // @phpstan-ignore-next-line
            $options .= sprintf('<option value="">%s</option>', $this->get('_first_option'));
        }
        /** @var array<string|int,string> $optionsList */
        $optionsList = $this->get('_options') ?: [];
        foreach ($optionsList as $k => $v) {
            $selected = '';
            if ((is_string($value) && $k == $value) || (is_array($value) && in_array($k, $value))) {
                $selected = 'selected="selected"';
            }
            $options .= sprintf('<option value="%s" %s>%s</option>', htmlentities((string) $k, ENT_COMPAT), $selected, $v);
        }

        return $options;
    }

    public function getInnerHtml(): string
    {
        return $this->getOptionsString();
    }
}
