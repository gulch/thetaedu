<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Template_Maintmpl extends Controller_Template{

    public $template = 'template/maintmpl';

    /**
     * The before() method is called before your controller action.
     * In our template controller we override this method so that we can
     * set up default values. These variables are then available to our
     * controllers if they need to be modified.
     */
    public function before() {
        parent::before();
        if ($this->auto_render) {
            // Initialize empty values
            $this->template->title   = 'Theta::Social Network for e-learning';
            $this->template->description = 'Social Network for e-learning.';
            $this->template->content = '';
            $this->template->styles = array();
            $this->template->scripts = array();
        }
    }

    /**
     * The after() method is called after your controller action.
     * In our template controller we override this method so that we can
     * make any last minute modifications to the template before anything
     * is rendered.
     */
    public function after() {
        if ($this->auto_render) {
            $styles = array(
                'assets/css/layout.css' => 'all',
                'assets/css/reset.css' => 'all',
                'assets/css/mainstyle.css' => 'all',
            );
            $scripts = array(
                'assets/script/atooltip.jquery.js',
                'assets/script/script.js',
            );
            $this->template->styles = array_merge( $this->template->styles, $styles );
            $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        }
        parent::after();
    }
}