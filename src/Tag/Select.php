<?php
namespace Sirius\Html\Tag;

class Select extends Input
{
    protected $tag = 'select';

    protected $isSelfClosing = false;

    /**
     * Generates the string with the list of the <OPTIONS> elements
     *
     * @return string
     */
    protected function getOptionsString()
    {
        $value   = $this->getValue();
        $options = '';
        if ($this->get('_first_option')) {
            $options .= sprintf('<option value="">%s</option>', $this->get('_first_option'));
        }
        $optionsList = $this->get('_options') ?: array();
        foreach ($optionsList as $k => $v) {
            $selected = '';
            if ((is_string($value) && $k == $value) || (is_array($value) && in_array($k, $value))) {
                $selected = 'selected="selected"';
            }
            $options .= sprintf('<option value="%s" %s>%s</option>', htmlentities($k, ENT_COMPAT), $selected, $v);
        }

        return $options;
    }

    public function getInnerHtml()
    {
        return $this->getOptionsString();
    }
}
