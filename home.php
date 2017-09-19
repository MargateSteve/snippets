<?php

/**
 * Root Controller
 *
 * Controller for any functions called from the site root as well
 * as the home page itself.
 *
 * This file cannot contain any functions with the same name as a level 1
 * directory in /app/controllers/
 *
 * Use this file sparingly and only for items that only have one variant.
 *
 * For anything that may have several pages, create a new controller file
 * or if there will be many or complex pages, a new folder.
 *
 * Functions
 * index ()     The site home page
 * register ()  The registration page
 * login ()     The full login page with help
 *
 * @package Core
 * @author  Steve Ball <steve@follyball.co.uk>
 * @copyright Copyright (c) 2017 Steve Ball <steve@follyball.co.uk>
 *
 */

class Home extends Controller
{
	/**
	 * Site Home Page
	 *
	 * This is the page that will be seen when viewing the site root.
	 *
	 * This will generally show latest updates as the primary focus
	 * but for now, we will just return some basic details from the
	 * various sections.
	 *
	 * @return 	view
	 * @link 	http://site.com/
	 */
	public function index($params=null)
	{
        /*
            If we have parameters at a controller root there has
            been a url error so send to the 404 page
         */
        if($params){Redirect::to('404');}

		/*
			Pass the required data through to the view

            For now we are just passing a few counts of various sections
		*/
	    $this->view(
	        'index',
	        [
	            // Pass the page title
	            'page_name' => Globals::get('site_settings_'.$this->site.'/site_name'),
                'type' => 'overview'
	        ]
	    );

  	} // index()

	/**
	 * Register
	 *
	 * Shows the registration form and processes any registration
	 * attempts to any non logged in users.
	 *
	 * If the user is logged in, they will be redirected to the index
	 * page.
	 *
	 * @return 	view
	 * @link 	http://site.com/Register
	 *
	 */
	public function register()
	{
        // Initianate any models that we will be using to pass data.
        $users_model = $this->model('Users_Model');

        // If the user is already logged in, redirect them back to the home page
        if($users_model->isLoggedIn())
        {
            Redirect::to('/');
        }

        /*
			We use $_errors to store any validation errors that may come from form posting. In create mode, that is the ony type of error we will recieve.

			$_errors is passed to the view as 'errors' but is only read there when
			$_posted = true to denote there has been a create attempt.

			If there has been a create attempt, if it returned any validation or token errors
			these will be passed to $_errors  and then the relevant info will be shown in the view. In the
			case of validation errors, it will also set validation classes on the form fields.

			If there has been a create attempt and no validation errors  have been generated, this
			will remain null and the view will show a message that the create has been successful.

			We set it to null initially and only populate it if required.
		*/
		$_errors = null;

		/*
			The view page is set-up to only look for errors or show a create success message if the
			form has been posted. To do this we pass $_posted to the view as 'posted'.

			If it exists, the view will then check to see if there are any errors. If there are, it will show them,
			otherwise it is safe to assume that the create has been successful so will show a success
			message.

			No messages will be shown in the view if $_posted remains false.

			We set it to null initially and only populate it if required.
		*/
		$_posted = false;

        /*
			Check for a form post

			We check to see if there is $_POST instance of the 'register' button. We would usually use the default submit button (set in
            the model) but for registration, we use a unique value.

			If there is, the first thing we do is set $_posted to true. This will tell the view that we will have
			some form of message, either errors io a success one,.

            We then attempt to create a new record using the create() function
            with a method of 'register' passed in. This tells the create script
            that it is a front end registration as opposed to a backend create
            so we need to check whether to automatically verify or send the
            verification email.

            There will be a validation attempt in the create() function so we
            assign any errors it returns to $_errors ready for displaying as
            well as setting validation classes on the form fields if required.
		 */
        if(null !== Input::get('register'))
		{
			$_posted = true;
			$_errors = $users_model->create(array('method' => 'register'));

		} // if posted

        /*
			Pass the data to the view.

			We use the index view in the users folder as these are theoretically user method even though they are being passed from the home page.

            We send 'type' as 'register' to tell the view that is what
            we want to display.

            We also pass 'class' as 'Users' so the correct model is
            chosen for the form.

            Finally we pass in whether or not the form has been posted as
            well as any errors that may have been returned.
		*/
	    $this->view(
	        'users/index',
	        [
                // Pass the page title
				'page_name' => 'Register',

                // Pass the class name to automatically call the correct model and core classes
                'class' => 'Users',

                // Pass the method type that we are using
                'type' => 'register',

                // Pass in the posted status - this tells the view that the form has been posted and may require a message to be show
                'posted' => $_posted,

                // Pass in any errors that have been set
                'errors' => $_errors
	        ]
	    );
  	} // register()


    /**
     * Login Page
     *
     * Shows the login help page with instructions for resetting the password.
     *
     * Only returns the view as everything is actually called from within the view.
     *
     * @return view
     * @link 	http://site.com/Login
     */
    public function login ()
    {
        // Initianate any models that we will be using to pass data.
        $users_model = $this->model('Users_Model');

        // If the user is already logged in, redirect them back to the home page
        if($users_model->isLoggedIn())
        {
            Redirect::to('/');
        }


        /*
            Pass the data to the view

            We only pass the actual page name and a'type' as 'register' to
            tell the view that is what we want to login.

         */
        $this->view(
            'users/index',
            [
                'page_name' => 'Login',
                'type' => 'login',
            ]
        );
    } // login ()


}
