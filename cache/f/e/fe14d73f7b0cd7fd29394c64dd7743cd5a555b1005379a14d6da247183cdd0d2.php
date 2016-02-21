<?php

/* base.php */
class __TwigTemplate_fe14d73f7b0cd7fd29394c64dd7743cd5a555b1005379a14d6da247183cdd0d2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
            'footer' => array($this, 'block_footer'),
            'scripting' => array($this, 'block_scripting'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
    ";
        // line 3
        $this->displayBlock('head', $context, $blocks);
        // line 22
        echo "
</head>

<body class=\"boxed\">

    <!-- FACEBOOK WIDGET -->
    <div id=\"fb-root\"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0\";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- /FACEBOOK WIDGET -->
    <div class=\"global-wrap\">
        <header id=\"main-header\">
            <div class=\"header-top\">
                <div class=\"container\">
                    <div class=\"row\">
                        <div class=\"col-md-3\">
                            <a class=\"logo\" href=\"index.html\">
                                <img src=\"img/logo-invert.png\" alt=\"iHotel Logo\" title=\"iHotel.mn\" />
                            </a>
                        </div>
                        <div class=\"col-md-3 col-md-offset-2\">
                            
                        </div>
                        <div class=\"col-md-4\">
                            <div class=\"top-user-area clearfix\">
                                <ul class=\"top-user-area-list list list-horizontal list-border\">

                                    <li class=\"nav-drop\"><a href=\"#\">Төгрөг ₮<i class=\"fa fa-angle-down\"></i><i class=\"fa fa-angle-up\"></i></a>
                                        <ul class=\"list nav-drop-menu\">
                                            <li><a href=\"#\">Доллар<span class=\"right\">\$</span></a>
                                            </li>
                                           
                                           
                                        </ul>
                                    </li>

                                    
                                    <li class=\"top-user-area-avatar\">
                                        <a href=\"user-profile.html\">
                                            <img class=\"origin round\" src=\"img/40x40.png\" alt=\"Image Alternative text\" title=\"AMaze\" />Hi, John</a>
                                    </li>
                                    
                                    

                                    <li><a href=\"#\">Sign Out</a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=\"container\">
                <div class=\"nav\">
                    
                </div>
            </div>
        </header>
        ";
        // line 89
        $this->displayBlock('content', $context, $blocks);
        // line 92
        echo "        ";
        $this->displayBlock('footer', $context, $blocks);
        // line 159
        echo "        ";
        $this->displayBlock('scripting', $context, $blocks);
        // line 181
        echo "    </div>
</body>
</html>


";
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "        <title>";
        $this->displayBlock('title', $context, $blocks);
        echo " ";
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo " </title>
        <meta content=\"text/html;charset=utf-8\" http-equiv=\"Content-Type\">
        <meta name=\"keywords\" content=\"hotel, Booking, Mongolia, iHotel\" />
        <meta name=\"description\" content=\"iHotel.mn\">
        <meta name=\"author\" content=\"iHotel.mn\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <!-- GOOGLE FONTS -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
        <!-- /GOOGLE FONTS -->
        <link rel=\"stylesheet\" href=\"css/bootstrap.css\">
        <link rel=\"stylesheet\" href=\"css/font-awesome.css\">
        <link rel=\"stylesheet\" href=\"css/icomoon.css\">
        <link rel=\"stylesheet\" href=\"css/styles.css\">
        <link rel=\"stylesheet\" href=\"css/mystyles.css\">
        <link rel=\"stylesheet\" href=\"css/hippie-blue.css\"/> 
        <script src=\"js/modernizr.js\"></script>
    ";
    }

    public function block_title($context, array $blocks = array())
    {
        echo " ";
    }

    // line 89
    public function block_content($context, array $blocks = array())
    {
        // line 90
        echo "
        ";
    }

    // line 92
    public function block_footer($context, array $blocks = array())
    {
        // line 93
        echo "            <footer id=\"main-footer\">
                <div class=\"container\">
                    <div class=\"row row-wrap\">
                        <div class=\"col-md-3\">
                            <a class=\"logo\" href=\"index.html\">
                                <img src=\"img/logo-invert.png\" alt=\"Image Alternative text\" title=\"Image Title\" />
                            </a>
                            <p class=\"mb20\">Зочид буудал захиалга</p>
                            <ul class=\"list list-horizontal list-space\">
                                <li>
                                    <a class=\"fa fa-facebook box-icon-normal round animate-icon-bottom-to-top\" href=\"#\"></a>
                                </li>
                                <li>
                                    <a class=\"fa fa-twitter box-icon-normal round animate-icon-bottom-to-top\" href=\"#\"></a>
                                </li>
                                <li>
                                    <a class=\"fa fa-google-plus box-icon-normal round animate-icon-bottom-to-top\" href=\"#\"></a>
                                </li>
                                <li>
                                    <a class=\"fa fa-linkedin box-icon-normal round animate-icon-bottom-to-top\" href=\"#\"></a>
                                </li>
                                <li>
                                    <a class=\"fa fa-pinterest box-icon-normal round animate-icon-bottom-to-top\" href=\"#\"></a>
                                </li>
                            </ul>
                        </div>

                        <div class=\"col-md-3\">
                            <h4>Мэдээлэл хүлээн авах</h4>
                            <form>
                                <label>И-мэйл хаягаа оруулна уу.</label>
                                <input type=\"text\" class=\"form-control\">
                                
                                <input type=\"submit\" class=\"btn btn-primary\" value=\"Subscribe\">
                            </form>
                        </div>
                        <div class=\"col-md-2\">
                            <ul class=\"list list-footer\">
                                <li><a href=\"#\">Бидний тухай</a>
                                </li>
                                <li><a href=\"#\">Блог</a>
                                </li>
                                <li><a href=\"#\">Тусламж</a>
                                </li>
                                <li><a href=\"#\">Үйлчилгээний нөхцөл</a>
                                </li>
                                <li><a href=\"#\">Асуулт хариулт</a>
                                </li>
                                <li><a href=\"#\">Холбогдох</a>
                                </li>
                                <li><a href=\"#\">Санал хүсэлт</a>
                                </li>
                                
                            </ul>
                        </div>
                        <div class=\"col-md-4\">
                            <h4>Бидэнтэй холбогдох</h4>
                            <h4 class=\"text-color\">+976-70110011</h4>
                            <h4><a href=\"#\" class=\"text-color\">info@ihotel.mn</a></h4>
                            <p>24/7 үйлчилгээний лавлах</p>
                        </div>

                    </div>
                </div>
            </footer>
        ";
    }

    // line 159
    public function block_scripting($context, array $blocks = array())
    {
        // line 160
        echo "            <script src=\"js/jquery.js\"></script>
            <script src=\"js/bootstrap.js\"></script>
            <script src=\"js/slimmenu.js\"></script>
            <script src=\"js/bootstrap-datepicker.js\"></script>
            <script src=\"js/bootstrap-timepicker.js\"></script>
            <script src=\"js/nicescroll.js\"></script>
            <script src=\"js/dropit.js\"></script>
            <script src=\"js/ionrangeslider.js\"></script>
            <script src=\"js/icheck.js\"></script>
            <script src=\"js/fotorama.js\"></script>
            <script src=\"https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false\"></script>
            <script src=\"js/typeahead.js\"></script>
            <script src=\"js/card-payment.js\"></script>
            <script src=\"js/magnific.js\"></script>
            <script src=\"js/owl-carousel.js\"></script>
            <script src=\"js/fitvids.js\"></script>
            <script src=\"js/tweet.js\"></script>
            <script src=\"js/countdown.js\"></script>
            <script src=\"js/gridrotator.js\"></script>
            <script src=\"js/custom.js\"></script>
         ";
    }

    public function getTemplateName()
    {
        return "base.php";
    }

    public function getDebugInfo()
    {
        return array (  232 => 160,  229 => 159,  160 => 93,  157 => 92,  152 => 90,  149 => 89,  119 => 4,  116 => 3,  107 => 181,  104 => 159,  101 => 92,  99 => 89,  30 => 22,  28 => 3,  24 => 1,);
    }
}
