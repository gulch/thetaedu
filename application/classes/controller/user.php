<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template_Website {

    public function action_index()
   	{
   		// Load the user information
   		$user = Auth::instance()->get_user();

   		// if a user is not logged in, redirect to login page
   		if (!$user)
   		{
               if(Request::$current->uri()!='login' && Request::$current->uri()!='')
               {
                    Request::current()->redirect('login?req='.Request::$current->uri());
               }else Request::current()->redirect('login');
   		}
        $this->template->content = View::factory('user/info')
            ->bind('user', $user);
   	}

   	public function action_create()
   	{
   		$this->template->content = View::factory('user/create')
   			->bind('errors', $errors)
   			->bind('message', $message);

   		if (HTTP_Request::POST == $this->request->method())
   		{
   			try {

   				// Create the user using form values
   				$user = ORM::factory('user')->create_user($this->request->post(), array(
   					'username',
   					'password',
   					'email'
   				));

   				// Grant user login role
   				$user->add('roles', ORM::factory('role', array('name' => 'login')));

   				// Reset values so form is not sticky
   				$_POST = array();

   				// Set success message
   				$message = __('user.registersucc');

   			} catch (ORM_Validation_Exception $e) {

   				// Set failure message
   				$message = __('user.loginerror');

   				// Set errors using custom messages
   				$errors = $e->errors('models');
   			}
   		}
   	}

   	public function action_login()
   	{
   		$this->template->content = View::factory('user/login')
   			->bind('message', $message);

   		if (HTTP_Request::POST == $this->request->method())
   		{
   			// Attempt to login user
   			$remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
   			$user = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $remember);

   			// If successful, redirect user
   			if ($user)
   			{
                if(!Model::factory('profile')->is_profile_record())
                {
                    if(Model::factory('profile')->create_profile())
                    {
                        Request::current()->redirect('profile/edit');
                    }
                    else
                    {
                        Request::current()->redirect('error/500');
                    }
                }
                else
                {
                    if($this->request->post('redirect')!='')
                    {
                        Request::current()->redirect($this->request->post('redirect'));
                    }else Request::current()->redirect('profile');
                }
   			}
   			else
   			{
   				$message = __('user.invalidlogin');
   			}
   		}
   	}

   	public function action_logout()
   	{
   		// Log user out
   		Auth::instance()->logout();

   		// Redirect to login page
   		Request::current()->redirect('login');
   	}

    public function action_soclogin()
    {
        if (HTTP_Request::GET == $this->request->method())
        {
            echo $this->request->query('provider');
            
            echo '<br><br>fuck!!!'; exit();
        }
    }

   }