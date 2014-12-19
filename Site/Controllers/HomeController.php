<?php
use Framework\ViewRendering\FileReader;
use Framework\ViewRendering\ViewRenderer;

/**
 * Class HomeController
 * @package Content\Controllers
 */
class HomeController
{
    /**
     *
     */
    public function index()
    {
        $views = new FileReader(__DIR__ . '\..\Views\Home');
        $renderer = new ViewRenderer($views);
        echo $renderer->render('index.html', 'Index page');
    }
}