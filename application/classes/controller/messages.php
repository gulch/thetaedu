<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Messages extends Controller_Template_Maintmpl {
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
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
        $this->template->content = View::factory('messages',array('messages_list'=>Model::factory('api')->get_messages_list(0)));
    }

    public function action_board()
    {
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
        $this->template->content = View::factory('boards',array('board_list'=>Model::factory('api')->get_board_list(0)));
    }
}