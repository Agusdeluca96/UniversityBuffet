<?php

require_once './vendor/autoload.php';

abstract class TwigView {

    private static $twig;

    public static function getTwig() {

        if (!isset(self::$twig)) {

            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem('./templates');
            self::$twig = new Twig_Environment($loader, array('cache' => false));
        }
        return self::$twig;
    }

    protected function returnTwig() {
    	$templateDir="./templates";
		$templateDirCompi="./templates-c";
		$loader = new Twig_Loader_Filesystem($templateDir);
		$twig = new Twig_Environment($loader);
		return $twig;
    }

}
?>