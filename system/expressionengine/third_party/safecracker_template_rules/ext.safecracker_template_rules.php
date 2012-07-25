<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Safecracker_template_rules_ext
{
	public $settings = array();
	public $name = 'SafeCracker Template Rules';
	public $version = '1.0.0';
	public $description = 'Add rules to a SafeCracker form with the {rule:your_field="required"} tag';
	public $settings_exist = 'n';
	public $docs_url = 'http://github.com/rsanchez/safecracker_template_rules';
	
	/**
	 * constructor
	 * 
	 * @access	public
	 * @param	mixed $settings = ''
	 * @return	void
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		
		$this->settings = $settings;
	}
	
	/**
	 * activate_extension
	 * 
	 * @access	public
	 * @return	void
	 */
	public function activate_extension()
	{
		$hook_defaults = array(
			'class' => __CLASS__,
			'settings' => '',
			'version' => $this->version,
			'enabled' => 'y',
			'priority' => 10
		);
		
		$hooks[] = array(
			'method' => 'safecracker_entry_form_tagdata_start',
			'hook' => 'safecracker_entry_form_tagdata_start'
		);
		
		foreach ($hooks as $hook)
		{
			$this->EE->db->insert('extensions', array_merge($hook_defaults, $hook));
		}
	}
	
	/**
	 * update_extension
	 * 
	 * @access	public
	 * @param	mixed $current = ''
	 * @return	void
	 */
	public function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		
		$this->EE->db->update('extensions', array('version' => $this->version), array('class' => __CLASS__));
	}
	
	/**
	 * disable_extension
	 * 
	 * @access	public
	 * @return	void
	 */
	public function disable_extension()
	{
		$this->EE->db->delete('extensions', array('class' => __CLASS__));
	}
	
	/**
	 * settings
	 * 
	 * @access	public
	 * @return	void
	 */
	public function settings()
	{
		$settings = array();
		
		return $settings;
	}
	
	public function safecracker_entry_form_tagdata_start($tagdata)
	{
		if ($this->EE->extensions->last_call !== FALSE)
		{
			$tagdata = $this->EE->extensions->last_call;
		}
		
		if ( ! preg_match_all('#{set_rules}(.*?){/set_rules}#s', $tagdata, $matches))
		{
			return $tagdata;
		}
		
		foreach ($matches[1] as $i => $_tagdata)
		{
			$tagdata = str_replace($matches[0][$i], '', $tagdata);
			
			$_tagdata = $this->EE->TMPL->simple_conditionals($_tagdata, $this->EE->safecracker->entry);
			$_tagdata = $this->EE->TMPL->advanced_conditionals($_tagdata);
			
			if ( ! preg_match_all('#{rules:([^=]+)=([\042\047])?(.*?)\\2}#', $_tagdata, $_matches))
			{
				continue;
			}
			
			foreach ($_matches[1] as $j => $field)
			{
				$rules = $_matches[3][$j];
				
				$this->EE->safecracker->form_hidden('rules['.$field.']', $this->EE->safecracker->encrypt_input($rules));
			}
		}
		
		return $tagdata;
	}
}

/* End of file ext.extension.php */
/* Location: ./system/expressionengine/third_party/extension/ext.extension.php */