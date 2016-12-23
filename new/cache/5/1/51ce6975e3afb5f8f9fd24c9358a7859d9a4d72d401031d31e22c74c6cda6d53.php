<?php

/* index.php */
class __TwigTemplate_51ce6975e3afb5f8f9fd24c9358a7859d9a4d72d401031d31e22c74c6cda6d53 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("base.php", "index.php", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'head' => array($this, 'block_head'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.php";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Нүүр";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-10 col-md-offset-1\">
                <div class=\"row row-wrap\" data-gutter=\"120\">
                    <div class=\"col-md-4\">
                        <div class=\"thumb\">
                            <header class=\"thumb-header\"><i class=\"fa fa-briefcase box-icon-black round box-icon-big animate-icon-top-to-bottom\"></i>
                            </header>
                            <div class=\"thumb-caption\">
                                <h5 class=\"thumb-title\"><a class=\"text-darken\" href=\"#\">Combine & Save</a></h5>
                                <p class=\"thumb-desc\">Risus quisque egestas venenatis potenti lobortis senectus tellus sodales commodo</p>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"thumb\">
                            <header class=\"thumb-header\"><i class=\"fa fa-thumbs-o-up box-icon-black round box-icon-big animate-icon-top-to-bottom\"></i>
                            </header>
                            <div class=\"thumb-caption\">
                                <h5 class=\"thumb-title\"><a class=\"text-darken\" href=\"#\">Best Travel Agent</a></h5>
                                <p class=\"thumb-desc\">Montes dictumst tortor a suspendisse accumsan blandit adipiscing proin ut</p>
                            </div>
                        </div>
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"thumb\">
                            <header class=\"thumb-header\"><i class=\"fa fa-send box-icon-black round box-icon-big animate-icon-top-to-bottom\"></i>
                            </header>
                            <div class=\"thumb-caption\">
                                <h5 class=\"thumb-title\"><a class=\"text-darken\" href=\"#\">Best Destinations</a></h5>
                                <p class=\"thumb-desc\">Cubilia malesuada odio aptent est etiam mollis velit dictumst posuere</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=\"gap gap-small\"></div>
    </div>

    <div class=\"container\">
        <div class=\"gap\"></div>
        <h2 class=\"text-center\">Top Destinations</h2>
        <div class=\"gap\">
            <div class=\"row row-wrap\">
                <?php

                    require_once \"config.php\";
                    use Parse\\ParseObject;
                    use Parse\\ParseQuery;
                    \$query = new ParseQuery(\"hotel\");
                    \$query->equalTo(\"status\",1);
                    \$results = \$query->find();
                    for (\$i = 0; \$i < count(\$results); \$i++) {
                        \$object = \$results[\$i];
                        \$image = \$results[\$i]->get(\"cover_image\");
                        \$url = \$image->getURL();
                        \$city = \$results[\$i]->get(\"city\");
                        \$short_desc = \$results[\$i]->get(\"short_desc\");

                        echo \"
                            <div class='col-md-3'>
                                <div class='thumb'>
                                    <header class='thumb-header'>
                                        <a class='hover-img curved' href='#''> 
                                            <img src='\$url' alt='Image Alternative text' title='196_365' />
                                        </a>
                                    </header>
                                    <div class='img-left'>
                                        <img src='img/flags/32/fr.png' alt='Image Alternative text' title='Image Title' />
                                    </div>
                                    <div class='thumb-caption'>
                                        <h4 class='thumb-title'><a class='text-darken' href='#''>\$city</a></h4>
                                        <div class='thumb-caption'>
                                            <p class='thumb-desc'>\$short_desc</p>
                                        </div>
                                    </div>
                                </div>
                            </div>\";   
                    }
                ?>
            </div>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "index.php";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 8,  46 => 7,  39 => 5,  36 => 4,  30 => 3,  11 => 1,);
    }
}
