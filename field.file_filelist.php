<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams File List Field Type
 *
 * @package		PyroCMS\addons\shared_addons\Field Types
 * @author		Laurence Cope, Adapted from Samul Goodwin File Folders
 */
class Field_file_filelist
{
	public $field_type_slug			= 'file_filelist';
	public $db_col_type				= 'int';
	public $alt_process				= false;
	public $version					= '1.0';
	public $author					= array('name'=>'Laurence Cope', 'url'=>'');
	public $_folder_list			= array();
	public $custom_parameters		= array('folder_id');

	public function param_folder_id($value)
	{
		$options = $this->folders();
		return form_dropdown('folder_id', $options, $value, 'id="folder_id"');
	}
	
	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output($data, $entry_id, $field)
	{
		
		$forms = form_dropdown($data['form_slug'], $this->files($data['custom']['folder_id'], $field->is_required), $data['value'], 'id="'.$data['form_slug'].'"');
		
		return $forms;
	}

	// --------------------------------------------------------------------------

	/**
	 * Process before outputting
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function pre_output($input)
	{
		$folders = $this->folders('yes');
		
		if (trim($input) != '')
		{
			return $folders[$input];
		}
		else
		{
			return null;
		}
	}


	// --------------------------------------------------------------------------

	/**
	 * Process before outputting for the plugin
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function pre_output_plugin($input, $params)
	{
		$this->CI->load->library('files/files');
		$file = Files::get_file((int)$input);
		$folders = $this->folders('yes');

		if (trim($input) != '')
		{
			$return['id']			= (int)$input;
			$return['filename']		= $file['data']->filename;		//File name of the image.
			$return['image']		= $file['data']->path;			//Full path to the image.
			$return['path']			= $file['data']->path;			//Full path to the image.
			$return['description']	= $file['data']->description;	//The image description.
			$return['ext']			= $file['data']->extension;		//The image extension.
			$return['mimetype']		= $file['data']->mimetype;		//The image mimetype.
			$return['width']		= $file['data']->width;			//Width of the full image.
			$return['height']		= $file['data']->height;		//Height of the full image.
			
			return $return;
		}
		else
		{
			return null;
		}
	}
	
	/**
	* Get a List of Files 
	*/
	private function files($folder_id, $is_required)
	{

		$this->CI->load->library('files/files');

		$choices = array();
	
		if ($is_required == 'no')
		{
			$choices[null] = get_instance()->config->item('dropdown_choose_null');
		}

		$files = Files::folder_contents($folder_id);
		
		// Convert array of objects to array of arrays
		foreach($files['data']['file'] AS $files_object)
		{
			$files_array[] = (array)$files_object;
		}
		
		// Sort array by name
		usort($files_array, array('Field_file_filelist','compareByName'));

		foreach($files_array AS $file)
		{
			$choices[$file['id']] = $file['name'];
		}
		
		return $choices;

	}

	/**
	* Get a List of Folders 
	*/
	private function folders()
	{

		$this->CI->load->library('files/files');

		$choices = array();

		$this->_build_tree_select(Files::folder_tree());

		return $choices + $this->_folder_list;
	}
	
	/**
	* Build the folder hierarchy
	*/
	private function _build_tree_select($folders, $selected = 0, $level = 0)
	{

		foreach ($folders AS $folder)
		{
			if ($level > 0)
			{
				$indent = '';
				for ($i = 0; $i < ($level*2); $i++)
				{
					$indent .= '&nbsp;';
				}

				$indent .= '-&nbsp;';
			}

			$this->_folder_list[$folder['id']] = $indent . $folder['name']; 
			
			if ( isset( $folder["children"] ) )
			{				
				 $this->_build_tree_select($folder['children'], $selected, $level + 1) ;
			}

		}
	}

	function compareByName($a, $b)
	{
		return strcasecmp($a["name"], $b["name"]);
    }

}