<?php
use Framework\BaseClasses\HtmlController;
use Framework\FileIo\FileReader;
use Framework\ViewRendering\ViewRenderer;

/**
 * Class HomeController
 * @package Content\Controllers
 */
class HomeController extends HtmlController
{
    public function index()
    {
        $views = new FileReader(__DIR__ . '\..\Views\Home');
        $renderer = new ViewRenderer($views);
        echo $renderer->render('index.html', 'Index page');
    }
}