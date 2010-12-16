<?php
class robotxtr extends Plugin
{
		
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
