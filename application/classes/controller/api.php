<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Api extends Controller {
    public function before()
    {
        parent::before();
        // if a user is not logged in, redirect to login page
        if (!Auth::instance()->get_user())
        {
            echo 'DENY'; exit();
        }
    }

    // Set Profile Image Action
    public function action_profile_setimage()
    {
        if (!empty($_FILES['profileImage']['name']))
        {
            echo Model::factory('api')->set_profile_image();
        }
        else
        {
            echo 'ERROR'; exit();
        }
    }

    // All List of Towns For Autocomplete
    public function action_profile_getalltown()
    {
        if (HTTP_Request::GET == $this->request->method())
        {
            echo Model::factory('api')->get_listof_all_towns();
        }
    }

    // All List of Language For TokenInput
    public function action_profile_getalllang()
    {
        if (HTTP_Request::GET == $this->request->method())
        {
            echo Model::factory('api')->get_listof_all_lang();
        }
    }

    // Save profile data
    public function action_profile_save()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->save_profile();
        }
    }

    public function action_profile_follow()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->follow_profile();
        }
    }
    public function action_profile_unfollow()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->unfollow_profile();
        }
    }

    public function action_profile_subscribe()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->subscribe_profile();
        }
    }

    public function action_message_checkread()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->message_checkread();
        }
    }

    public function action_message_delete()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->message_delete();
        }
    }

    public function action_message_send()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->message_send();
        }
    }

    public function action_publication_rate()
    {
        echo Model::factory('api')->publication_rate($this->request->param('id'));
    }

    public function action_get_predmets()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->get_predmets();
        }
    }

    public function action_publication_save()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->publication_save();
        }
    }

    public function action_publication_new()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->publication_new();
        }
    }

    public function action_publication_publish()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->publication_publish();
        }
    }

    public function action_task_cancel()
    {
      if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->task_cancel();
        }
    }

    public function action_task_save()
    {
        if (HTTP_Request::POST == $this->request->method())
        {
            echo Model::factory('api')->task_save();
        }
    }
}