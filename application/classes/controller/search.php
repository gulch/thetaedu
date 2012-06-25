<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller_Template_Maintmpl{

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
        if(Request::$current->query('v')=='all')
        {
            $this->template->content = View::factory('searchpublications',array('publication_list'=>Model::factory('api')->get_all_publications(0)));
        }
        else
        {
            if(Request::$current->query('g'))
            {
                $this->template->content = View::factory('searchpublications',array('publication_list'=>Model::factory('api')->get_all_publications_by_galuz(Request::$current->query('g'),0)));
            }
            else
            {
                if(Request::$current->query('p'))
                {
                    $this->template->content = View::factory('searchpublications',array('publication_list'=>Model::factory('api')->get_all_publications_by_predmet(Request::$current->query('p'),0)));
                }
                else
                {
                    if(Request::$current->query('sp'))
                    {
                        $this->template->content = View::factory('search',array('publication_list'=>Model::factory('api')->get_search_publications(Request::$current->query('sp'),0),
                                                                                'searchtext'=>Request::$current->query('sp'),
                                                                                'searchtype'=>1));
                    }
                    else
                    {
                        if(Request::$current->query('su'))
                        {
                            $this->template->content = View::factory('search',array('users_list'=>Model::factory('api')->get_search_users(Request::$current->query('su'),0),
                                                                                    'searchtext'=>Request::$current->query('su'),
                                                                                    'searchtype'=>2));
                        }
                        else
                        {
                            $this->template->content = View::factory('search',array('searchtext'=>'',
                                                                                    'searchtype'=>0));
                        }
                    }
                }
            }
        }
        $this->template->header = View::factory('header', Model::factory('profile')->get_profile_menu());
    }
}