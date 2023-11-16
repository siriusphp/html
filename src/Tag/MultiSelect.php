<?php

namespace Sirius\Html\Tag;

class MultiSelect extends Select
{
    /**
     * Generates the string with the list of the <OPTIONS> elements
     *
     * @return string
     */
    protected function getOptionsString(): string
    {
        $value   = $this->getValue();
        $options = '';
        if ($this->get('_first_option')) {
            $options .= sprintf('<option value="">%s</option>', $this->get('_first_option')); // @phpstan-ignore-line
        }
        /** @var array<int|string,string> $optionsList */
        $optionsList = $this->get('_options') ?: [];
        foreach ($optionsList as $k => $v) {
            $selected = '';
            // be flexible, accept a non-array value
            if ((is_string($value) && $k == $value) || (is_array($value) && in_array($k, $value))) {
                $selected = 'selected="selected"';
            }
            $options .= sprintf('<option value="%s" %s>%s</option>', htmlentities((string) $k, ENT_COMPAT), $selected, $v);
        }

        return $options;
    }

    public function render()
    {
        /** @var string $name */
        $name = $this->get('name');
        if (!str_ends_with($name, '[]')) {
            $this->set('name', $name . '[]');
        }

        return parent::render();
    }
}
