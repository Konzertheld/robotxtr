<?php
class robotxtr extends Plugin
{
	
	 /**
     * Filter function called by the plugin hook `rewrite_rules`
     * Add a new rewrite rule to the database's rules.
     *
     * Call `robotxtr::act('Robots')` when a request for `robots.txt` is received.
     *
     * @param array $db_rules Array of rewrite rules compiled so far
     * @return array Modified rewrite rules array, we added our custom rewrite rule
     */
    public function filter_rewrite_rules( $db_rules )
    {
	$db_rules[]= RewriteRule::create_url_rule( '"robots.txt"', 'robotxtr', 'Robots' );
	return $db_rules;
    }
	
		
	/**
    * Act function called by the `Controller` class.
    * Dispatches the request to the proper action handling function.
    *
    * @param string $action Action called by request, we only support 'Sitemap'
    */
    public function act( $action )
    {
	switch ( $action )
	{
	    case 'Robots':
		self::Robots();
		break;
	}
    }

    public function Robots()
    {
    
    $robots = Options::get('robotxtr__content'); 
    
	ob_clean();
	header( 'Content-Type: text/plain' );
	print $robots;
	}
	
	public function filter_plugin_config( $actions, $plugin_id )
	{
		if ( $this->plugin_id() == $plugin_id ) {
			$actions[]= _t('Configure');
		}
		return $actions;
	}

	public function action_plugin_ui( $plugin_id, $action )
	{
		if ( $this->plugin_id() == $plugin_id && $action == _t('Configure') ){
			$form = new FormUI('mint');
			$form->append('textarea', 'robotxtr_content', 'robotxtr__content', _t('Enter content of robots.txt'));
			$form->append( 'submit', 'save', 'Save' );
			$form->on_success( array($this, 'save_config') );
			$form->out();
		}
	}

	/**
	 * Invoked when the before the plugin configurations are saved
	 *
	 * @param FormUI $form The configuration form being saved
	 * @return true
	 */
	public function save_config( FormUI $form )
	{
		Session::notice( _t('robotxtr plugin configuration saved', 'robotxtr') );
		$form->save();
	}

	public function action_plugin_deactivation()
	{
		Options::delete('robotxtr__content');
	}

	public function action_plugin_activation()
	{
		Options::set('robotxtr__content', '');
	}
	
}
?>
