<?php

namespace App\Controller;

class Controller
{
    protected $service;

    /**
     * Render view
     *
     * @param string $viewName Name of the view location in View/ folder
     * @param array $params variables that will be provided to view
     */
    protected function render($viewName, array $params = [])
    {
        $path = dirname(__FILE__, 2).'/View/'.$viewName.'.php';
        ob_start();
        extract($params);
        require($path);
        $var = ob_get_contents();
        ob_end_clean();
        echo $var;
    }
}
