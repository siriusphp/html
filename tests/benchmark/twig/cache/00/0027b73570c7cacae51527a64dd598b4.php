<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* index.html */
class __TwigTemplate_e79ecc2cc625bbb04a1901ff75a03f55 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, ($context["form_url"] ?? null), "html_attr");
        echo "\">

    ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["fields"] ?? null));
        foreach ($context['_seq'] as $context["input_name"] => $context["field"]) {
            // line 4
            echo "        <div class=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["classes"] ?? null), "form_group", [], "any", false, false, false, 4), "html_attr");
            echo "\">
            <label class=\"";
            // line 5
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["classes"] ?? null), "form_label", [], "any", false, false, false, 5), "html_attr");
            echo "\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "label", [], "any", false, false, false, 5), "html", null, true);
            echo "</label>
            <input type=\"text\" name=\"";
            // line 6
            echo twig_escape_filter($this->env, $context["input_name"], "html_attr");
            echo "\" value=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["field"], "value", [], "any", false, false, false, 6), "html_attr");
            echo "\" class=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["classes"] ?? null), "form_control", [], "any", false, false, false, 6), "html_attr");
            echo "\">
        </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['input_name'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "
    <button type=\"submit\" class=\"";
        // line 10
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["classes"] ?? null), "submit", [], "any", false, false, false, 10), "html_attr");
        echo "\">Submit</button>
</form>";
    }

    public function getTemplateName()
    {
        return "index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 10,  71 => 9,  58 => 6,  52 => 5,  47 => 4,  43 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "index.html", "/var/www/html/Html/tests/benchmark/twig/templates/index.html");
    }
}
