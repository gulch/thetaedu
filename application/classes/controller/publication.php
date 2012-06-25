<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Publication extends Controller_Template_Maintmpl{

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

    public function action_view()
    {
        $id = $this->request->param('id');
        if(!$id) Request::current()->redirect('error/400');

        $scripts = array(
            'assets/script/opineo.js'
        );
        $styles = array(
            'assets/css/opineo.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );


        $this->template->content = View::factory('viewpublication', Model::factory('publication')->get_publication_for_view($id));
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }

    public function action_edit()
    {
        $id = $this->request->param('id');
        if(!$id) Request::current()->redirect('error/400');

        $scripts = array(
            'assets/script/interface.js',
            'assets/script/elrte.full.js',
            'assets/script/jquery-ui-1.8.13.custom.min.js',
            'assets/script/i18n/elrte.uk.js'
        );
        $styles = array(
            'assets/css/elrte.full.css' => 'all',
            'assets/css/smoothness/jquery-ui-1.8.13.custom.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );

        $this->template->content = View::factory('publicationedit', Model::factory('publication')->get_publication_for_edit($id));
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }

    public function action_create()
    {
        $scripts = array(
            'assets/script/interface.js',
            'assets/script/elrte.full.js',
            'assets/script/jquery-ui-1.8.13.custom.min.js',
            'assets/script/i18n/elrte.uk.js'
        );
        $styles = array(
            'assets/css/elrte.full.css' => 'all',
            'assets/css/smoothness/jquery-ui-1.8.13.custom.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );

        $this->template->content = View::factory('publicationnew', Model::factory('publication')->get_publication_for_new());
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }

    public function action_delete()
    {
        $id = $this->request->param('id');
        if(!$id) Request::current()->redirect('error/400');

        Model::factory('publication')->delete_publication($id);
        Request::$current->redirect('/search');
    }

    public function action_tasks()
    {
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
        $this->template->content = View::factory('tasks',array('tasks_list'=>Model::factory('api')->get_tasks_list(0)));
    }

    public function action_edit_task()
    {
        $id = $this->request->param('id');
        if(!$id) Request::current()->redirect('error/400');

        $scripts = array(
            'assets/script/elrte.full.js',
            'assets/script/jquery-ui-1.8.13.custom.min.js',
            'assets/script/i18n/elrte.uk.js',
            'assets/script/jquery.slider.js',
            'assets/script/jquery.dependClass.js',
        );
        $styles = array(
            'assets/css/elrte.full.css' => 'all',
            'assets/css/smoothness/jquery-ui-1.8.13.custom.css' => 'all',
            'assets/css/jslider.css' => 'all',
            'assets/css/jslider.round.plastic.css' => 'all',
        );
        $this->template->scripts = array_merge( $this->template->scripts, $scripts );
        $this->template->styles = array_merge( $this->template->styles, $styles );

        $this->template->content = View::factory('taskedit', Model::factory('publication')->edit_task($id));
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());

        Model::factory('publication')->edit_task($id);
    }

}