<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Profile extends Controller_Template_Maintmpl{

    public function before()
    {
        parent::before();
        // Load the user information
        $user = Auth::instance()->get_user();
        // if a user is not logged in, redirect to login page
        if (!$user)
        {
            Request::current()->redirect('login?req='.Request::$current->uri());
        }
    }

    public function action_index()
    {
        $id = null;
        if(Request::$current->query('id'))
        {
            $id = Request::$current->query('id');
        }
        $scripts = array(
            'assets/script/jquery.dd.js'
        );
        $styles = array(
            'assets/css/dd.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );
        $this->template->content = View::factory('profile/profile_view', Model::factory('profile')->get_profile_for_view($id));
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }

    public function action_uid()
    {
        $id = $this->request->param('id');

        if(!$id) Request::current()->redirect('error/400');

        //if($id==null) Request::current()->redirect('profile/index');
        $scripts = array(
            'assets/script/jquery.dd.js'
        );
        $styles = array(
            'assets/css/dd.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );
        $this->template->content = View::factory('profile/profile_view', Model::factory('profile')->get_profile_for_view($id));
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }

    public function action_edit()
    {
        $scripts = array(
                        'assets/script/ac/jquery.autocomplete.js',
                        'assets/script/jquery.html5uploader.min.js',
                        'assets/script/ac/jquery.ajaxQueue.js',
                        'assets/script/jquery.tokeninput.js',
                        'assets/script/jquery.dd.js'
                        );
        $styles = array(
                        'assets/script/ac/jquery.autocomplete.css' => 'all',
                        'assets/script/ac/thickbox.css' => 'all',
                        'assets/css/token-input.css' => 'all',
                        'assets/css/dd.css' => 'all',
                        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );
        $this->template->content = View::factory('profile/profile_edit', Model::factory('profile')->get_profile_for_edit());
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }
}