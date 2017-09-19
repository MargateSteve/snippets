<?php

/**
 * Football Network Main Template
 *
 * This file contains actual content of the page and builds the template
 * using the functions in Base_Template.
 *
 * @package 	Core
 * @author  	Steve Ball <steve@follyball.co.uk>
 * @copyright 	Copyright (c) 2017 Steve Ball <steve@follyball.co.uk>
 * @example 	new Main_Template($params)
 * @return 		Entire HTML page
 */
class Host_Template
{
    // To allow for multiple versions of the same template, we can pass in a version. Check content() in this Class to see how it is used

	/**
	 * Construct the Page
	 * @param string 		$main       Page Content
	 * @param string 		$version    Which version of the template to show
	 * @param string 		$page_title The page title to send to the <head> and title
	 * @param array/null 	$include    Additional css and js for the page
	 */
    public function __construct($content, $page_title, $include=null)
	{
		// Set the displayed content to $this->content
		$this->content = $content;

		// Set any extra required css or js $this->include
		//
		//
        $this->include = array (
            'css' => array (
                //'css/local/football_network'
            )
        );

		// If a Page Title has been passed set $this->page_title to it
		// otherwise use 'Untitled'
        if($page_title) {
            $this->page_title = $page_title;
        } else {
            $this->page_title = 'Untitled';
        }

		// Call the template function, including any extra required css or js
        self::Template($include);

    } // __construct


	/**
	 * Build the entire template
	 *
	 * We build this up gradually via functions contained eihter in this file or in
	 * the Base_Template file it extends
	 * @return 	HTML page
	 */
    private function Template ()
	{
		// Build the start of the page up to the opening body tag, including the <head>
		self::bodyStart ();

        self::topNav ();

		// Build the container that holds the content and set the Page Title
        self::contentStart ();

		// Show the actual content for the page
        self::content ();

		// Close the container that holds the content
        self::contentEnd ();

		// Show any requested scripts and close of the <body> and <html> tags
        self::bodyEnd ();

    } // Template()

    /**
     * Build the top navigation menu
     *
     * Sets the static parts of the menu and turns Globals::get('menus/main_top_nav')
     * in the required links and dropdowns
     *
     * @return string 	nav
     */
    public function topNav ()
    {
        $array = array (
    		'Home' => array (
    			'link', '/'
    		),
    		'Members' => array (
    			'link', '/Members/'
    		),
            'News' => array (
    			'link', '/News/'
    		),
            'Templates' => array (
    			'link', '/Templates'
    		),
    	);


        $out = '<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">';
        $out .= '	<div class="container">';
        $out .= '<a class="navbar-brand" href="#">Navbar</a>';
        $out .= '		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">';
        $out .= '			<span class="navbar-toggler-icon"></span>';
        $out .= '		</button>';
        $out .= '      <div class="collapse navbar-collapse" id="main-nav">';
        $out .= '      	<ul class="navbar-nav mr-auto">';

        /**
         * Loop through the menu array and build the menu
         *
         * If the 'type' is set as 'link' we will create a simple <li><a>.
         * If the 'type' is set as 'dropdown' we will create a dropdown
         * <li><a> with a div containing all the links.
         * @var array
         */
        foreach ($array as $key => $value)
        {
            // 'type' is set as 'link'
            if($value[0] == 'link') {
                /*
                    Build a <li> with the class of .nav-item
                    Inside this put an <a> with a value of $value[1] (the link)
                    and a display of $key (the link name)
                 */
                $out .= '<li class="nav-item">';
                $out .= '  <a class="nav-link" href="'.$value[1].'">'.$key.'</a>';
                $out .= '</li>';
            }

            // 'type' is set as 'dropdown'
            if($value[0] == 'dropdown') {
                /*
                    Build a <li> with the class of .nav-item dropdown .

                    Inside this, build an <a> with a class of dropdown-toggle. This
                    will then use the Bootstap toggle class.
                    Set the id to the $key (the link name) appended with '_menu'. This
                    will be to target the correct dropdown menu. Also set the display to $key.

                    Build a <div> with the class of .dropdown-menu. Give it an
                    aria-labelledby of $key appended with '_menu' so it matches
                    the <a> that triggers it.

                    Finally, inside the div, loop through the links (stored in $value[1]) and creat an <a> for each one.
                 */
                $out .= '<li class="nav-item dropdown">';
                $out .= '  <a class="nav-link dropdown-toggle" id="'.$key.'_menu" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$key.'</a>';
                $out .= '  <div class="dropdown-menu" aria-labelledby="'.$key.'_menu">';

                foreach ($value[1] as $menu => $item) {
                     $out .= '  <a class="dropdown-item" href="'.$item[1].'">'.$menu.'</a>';
                }

                $out .= '</div>';
                $out .= '</li>';
            }
        }

        $out .= '      	</ul><!-- #navbar-nav mr-auto -->';


        $out .= '<form class="form-inline my-2 my-lg-0">';
        $out .= '  <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">';
        $out .= '  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>';
    $out .= '    </form>';


        $out .= '      </div><!-- #main-nav -->';
        $out .= '	</div><!-- .container -->';
        $out .= '</nav><!-- nav -->';

        echo $out;
    } // topNav()

    	/**
    	 * Build the <head> section
    	 *
    	 * Builds the entire <head> tag and includes any specified
    	 * page specific css
    	 *
    	 * @return string          			<head> section
    	 */
        private function head ()
    	{
    		$title = isset($this->page_title) ? $this->page_title: Globals::get('settings/site_name');
    		// Open the <head> tag
    		$out = '<head>';
    			// Include the <meta> tags
                $out .=  ' <meta charset="utf-8">';

                // Include the <meta> tags
                $out .=  '<meta name="description" content="Admin Login">';
                $out .=  '<meta name="author" content="Steve Ball">';
    			$out .=  '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
    			$out .=  '<meta name="description" content="Framework - Front End">';
    			$out .=  '<meta name="keyword" content="Bootstrap,Admin,Template,PHP,MVC,Framework,jQuery,CSS,HTML,Dashboard">';
                $out .=  '<style>body {
                          padding-top: 3.5rem;
                        }</style>';
    			// Set the Page Title
    			$out .=  '<title>' . $title. '</title>';

    			// Link the Favicon
    			$out .=  '<link rel="shortcut icon" href="/favicon.ico">';

    			// Call in the stylesheets
    			$out .=  Globals::get('css/vendor/bootstrap/v4_beta');
    			$out .=  Globals::get('css/vendor/font-awesome');
    			$out .=  Globals::get('css/local/site');

    			// Include any page-specific css files
    			$out .=  getIncludeFiles ($this->include, 'css');

             	$out .=  '<!--[if lt IE 9]>';
            	$out .=  '   <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>';
            	$out .=  '  <![endif]-->';
            $out .=  '</head>';

    		echo $out;

        } // head()

    /**
     * Build the HTML down to the opening body tag and include extra css
     *
     * @return string 					Template to the opening <body> tag
     */
    public function bodyStart ()
    {

        // Start off by specifying the doctype
        $out = ' <!doctype html>';

        // Open the html tag
        $out .= '<html lang="en">';


        /**
         * Include the <head> section
         *
         * If a page needs any extra css apart from the default, this will
         * have been specified using an $include array when calling new Main_Template.
         *
         * These will be set using their Globals::get() reference
         *
         * @var array/null
         */
        $out .= self::head ();

        // Open the body tag
        $out .= '<body class="bg-dark text-light">';

        // Echo out everything created in the function
        echo $out;
    } // bodyStart()

    /**
     * Finish the HTML
     *
     * @return string 					Template for the end of the HTML
     */
    public function bodyEnd ()
    {

        /**
         * Include any extra js
         *
         * If a page needs any extra js apart from the default, this will
         * have been specified using an $include array when calling new Main_Template.
         *
         * These will be set using their Globals::get() reference
         *
         * @var array/null
         */
        $out = self::scripts ();

        // Close the body tag
        $out .= '</body>';

        // Close the html tag
        $out .= '</html>';

        // Echo out everything created in the function
        echo $out;

    } // bodyEnd()

    /**
	 * Build the required scripts
	 *
	 * Builds the js and third party scripts
	 *
	 * @return string          			<head> section
	 */
    private function scripts ()
	{
		// Include any common js files
        echo Globals::get('js/vendor/jquery');
        echo Globals::get('js/vendor/bootstrap/v4_beta');
		echo Globals::get('js/local/shared');
        echo Globals::get('js/local/default');

		// Include any page-specific js files
        getIncludeFiles ($this->include, 'js');

    } // scripts()


	/**
	 * Show the actual page content
	 *
	 * We show the actual content of the page using a pre-defined layout version passed
	 * in when calling the template.
	 *
	 * Each layout is created using makeRow() and makeColumn() functions in the
	 * Bootstrap class.
	 *
	 * We start by making a row with an id of 'main_content'.
	 * Inside that we place an array ($inc) of each seperate column builder
	 * function we require.
	 *
	 * The available columns are
	 * 	mainColumn_full ()		col-sm-12
	 * 		Outputs $this->content
	 *
	 * 	mainColumn ()			col-sm-9
	 * 		Outputs $this->content
	 *
	 * 	mainColumn_small() 		col-sm-6
	 * 		Outputs $this->content
	 *
	 * 	left_Column() 		col-sm-6
	 * 		Outputs specified data
	 *
	 * 	right_Column() 		col-sm-6
	 * 		Outputs specified data
	 *
	 * @return string  	Page content
	 */
    private function content ()
	{
        echo   Bootstrap::makeRow (
            $id = 'main_content',
            $class = '',
            $inc = array (
                Bootstrap::makeColumn (
                   	$id = 'main_content_inner ',
                   	$class = 'col-sm-12',
                   	$inc = array (
                       	$this->content
                   	)
                )
            )

        );

    } // content()


	/**
	 * Start the content
	 *
	 * @return string 	Start of the content section up to the masthead
	 */
    private function contentStart ()
    {
        echo  '<div class="container">';
        echo  '<div class="masthead mb-2 pb-2">';

        echo  ' <h2 class="display-2">'.$this->page_title.'</h1>';
        echo  ' </div>';








    } // contentStart()

	/**
	 * End the content
	 *
	 * @return string 	Closes the container
	 */
    private function contentEnd ()
    {
        echo  '</div> <!-- container -->';
    } // contentEnd()

	/**
	 * Test extra content
	 *
	 * Simply a spare function that you can call in any of the column functions
	 * to give an exaple of adding extra content to them.
	 * @return string
	 */
    private function extra ()
    {
		$out = '<div class="row">';
        $out .= '<h3>Bottom Column</h3>';
		$out .= '</div>' ;

        return $out;
    } // extra()

	/**
	 * Basic outputs for test
	 *
	 * These are just temporary functions to show something in the
	 * left and right columns.
	 *
	 * Both will be replaced with something more useful.
	 */
    private function left_user () {

        $out = '<div class="card menu">';
        $out .= '  <div class="card-block">';
        $out .= '   <h4 class="card-title">User Menu</h4>';
        $out .= '  </div>';

        $out .= '</div>';

        return $out;
    }

    private function left_menu ()
    {

        $out = '	    <div class="card menu">';
        $out .= '  <div class="card-block">';
        $out .= '   <h4 class="card-title">Batch Menu</h4>';
        $out .= '  </div>';
        $out .= '  <ul class="list-group list-group-flush">';
        $out .= '   <li class="list-group-item"><a href="/Selenium/batches" class="card-link">Home</a></li>';
        $out .= '    <li class="list-group-item">Dapibus ac facilisis in</li>';
        $out .= '    <li class="list-group-item">Vestibulum at eros</li>';
        $out .= ' </ul>';
        $out .= ' <div class="card-block">';
        $out .= '  <a href="#" class="card-link">Card link</a>';
        $out .= '   <a href="#" class="card-link">Another link</a>';
        $out .= ' </div>';
        $out .= '</div>';

        return $out;
    }

    /**
     * Create the top banner
     *
     * @return string 					Banner
     */
    public function banner ()
    {

        $out = '<div class="container-fluid bg-dark text-light pb-2">';
        $out .= '<div class="container">';
        $out .= '<h1>Main Template</h1>';
        $out .= '</div>';
        $out .= '</div>';

        // Echo out everything created in the function
        echo $out;

    } // banner()



}
