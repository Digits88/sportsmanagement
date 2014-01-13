<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.modellist' );



/**
 * sportsmanagementModelPredictionTemplates
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2013
 * @access public
 */
class sportsmanagementModelPredictionTemplates extends JModelList
{

	var $_identifier = "predictiontemplates";
	
	

	function getListQuery()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        // Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();
        
        // Create a new query object.
        $query = $this->_db->getQuery(true);
        $query->select(array('tmpl.*', 'u.name AS editor'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_template AS tmpl')
        ->join('LEFT', '#__users AS u ON u.id = tmpl.checked_out');

        if ($where)
        {
            $query->where($where);
        }
        if ($orderby)
        {
            $query->order($orderby);
        }

		
		return $query;
	}

	function _buildContentWhere()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');

		$filter_order		= $mainframe->getUserStateFromRequest( $option .'.'.$this->_identifier. 'tmpl_filter_order','filter_order','tmpl.title','cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option .'.'.$this->_identifier. 'tmpl_filter_order_Dir','filter_order_Dir','','word');

		$where = array();
		//$prediction_id = (int) $mainframe->getUserState( 'com_joomleague' . 'prediction_id_select' );
		$prediction_id = $mainframe->getUserStateFromRequest( $option .'.'.$this->_identifier, 'prediction_id_select', '0' );
        if ( isset($prediction_id->prediction_id_select) > 0 )
		{
			$where[] = 'tmpl.prediction_id = ' . $prediction_id->prediction_id_select;
		}
		$where 	= ( count( $where ) ? ''. implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildContentOrderBy()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');

		$filter_order		= $mainframe->getUserStateFromRequest( $option .'.'.$this->_identifier. 'tmpl_filter_order','filter_order','tmpl.title','cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option .'.'.$this->_identifier. 'tmpl_filter_order_Dir','filter_order_Dir','','word');

		if ( $filter_order == 'tmpl.title' )
		{
			$orderby 	= 'tmpl.title ' . $filter_order_Dir;
		}
		else
		{
			$orderby 	= '' . $filter_order . ' ' . $filter_order_Dir . ' , tmpl.title ';
		}

		return $orderby;
	}

	

	
	/**
	 * check that all prediction templates in default location have a corresponding record, except if game has a master template
	 *
	 */
	function checklist($prediction_id)
	{
	  $mainframe		= JFactory::getApplication();
      $option = JRequest::getCmd('option');
		//$prediction_id	= $this->_prediction_id;
		//$defaultpath	= JLG_PATH_EXTENSION_PREDICTIONGAME.DS.'settings';
		$defaultpath	= JPATH_COMPONENT_SITE.DS.'settings';
    //$extensionspath	= JPATH_COMPONENT_SITE . DS . 'extensions' . DS;
    // Get the views for this component.
	$path = JPATH_SITE.'/components/'.$option.'/views';
        
		$templatePrefix	= 'prediction';
//    $defaultvalues = array();
    
		if (!$prediction_id){return;}

		// get info from prediction game
		$query = 'SELECT master_template 
					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_game 
					WHERE id = ' . (int) $prediction_id;

		$this->_db->setQuery($query);
		$params = $this->_db->loadObject();

		// if it's not a master template, do not create records.
		if ($params->master_template){return true;}

		// otherwise, compare the records with the files // get records
		$query = 'SELECT template 
					FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_prediction_template 
					WHERE prediction_id = ' . (int) $prediction_id;

		$this->_db->setQuery($query);
		$records = $this->_db->loadResultArray();
		if (empty($records)){$records=array();}
    
		// add default folder
		$xmldirs[] = $defaultpath . DS . 'default';

		// now check for all xml files in these folders
		foreach ($xmldirs as $xmldir)
		{
			if ($handle = opendir($xmldir))
			{
				/* check that each xml template has a corresponding record in the
				database for this project. If not, create the rows with default values
				from the xml file */
				while ($file = readdir($handle))
				{
					if ($file!='.'&&$file!='..'&&strtolower(substr($file,(-3)))=='xml'&&
						strtolower(substr($file,0,strlen($templatePrefix)))==$templatePrefix)
					{
						$template = substr($file,0,(strlen($file)-4));
                        
                        //$mainframe->enqueueMessage(JText::_('PredictionGame template -> '.$template),'');
                        
						// Determine if a metadata file exists for the view.
				        //$metafile = $path.'/'.$template.'/metadata.xml';
                        $metafile = $path.'/'.$template.'/tmpl/default.xml';
                        $attributetitle = '';
                        if (is_file($metafile)) 
                        {
                        // Attempt to load the xml file.
					   if ($metaxml = simplexml_load_file($metafile)) 
                        {
                        //$mainframe->enqueueMessage(JText::_('PredictionGame template metaxml-> '.'<br /><pre>~' . print_r($metaxml,true) . '~</pre><br />'),'');    
                        // This will save the value of the attribute, and not the objet
                        //$attributetitle = (string)$metaxml->view->attributes()->title;
                        $attributetitle = (string)$metaxml->layout->attributes()->title;
                        //$mainframe->enqueueMessage(JText::_('PredictionGame template attribute-> '.'<br /><pre>~' . print_r($attributetitle,true) . '~</pre><br />'),'');
                        if ($menu = $metaxml->xpath('view[1]')) 
                        {
							$menu = $menu[0];
                            //$mainframe->enqueueMessage(JText::_('PredictionGame template menu-> '.'<br /><pre>~' . print_r($menu,true) . '~</pre><br />'),'');
                            }
                        }
                        }
                        
                        if ((empty($records)) || (!in_array($template,$records)))
						{
						  $jRegistry = new JRegistry();
							$form = JForm::getInstance($file, $xmldir.DS.$file);
							$fieldsets = $form->getFieldsets();
							
							//echo 'fieldsets<br /><pre>~' . print_r($fieldsets,true) . '~</pre><br />';
							//echo 'form<br /><pre>~' . print_r($form,true) . '~</pre><br />';
							
							$defaultvalues = array();
							foreach ($fieldsets as $fieldset) 
              {
								foreach($form->getFieldset($fieldset->name) as $field) 
                {
									//echo 'field<br /><pre>~' . print_r($field,true) . '~</pre><br />';
                  $jRegistry->set($field->name, $field->value);
                  $defaultvalues[] = $field->name.'='.$field->value;
								}				
							}
							$defaultvalues = $jRegistry->toString('ini');
                            
//$mainframe->enqueueMessage(JText::_('defaultvalues -> '.'<pre>'.print_r($defaultvalues,true).'</pre>' ),'');
                            
							//$defaultvalues = ereg_replace('"', '', $defaultvalues);
                            //$defaultvalues = preg_replace('"', '', $defaultvalues);
							//$defaultvalues = implode('\n', $defaultvalues);
							//echo 'defaultvalues<br /><pre>~' . print_r($defaultvalues,true) . '~</pre><br />';
							
							//$tblTemplate_Config = JTable::getInstance('predictiontemplate', 'table');
                            $mdl = JModel::getInstance("predictiontemplate", "sportsmanagementModel");
                            $tblTemplate_Config = $mdl->getTable();
							
                            $tblTemplate_Config->template = $template;
                            if ( $attributetitle )
                            {
                                $tblTemplate_Config->title = $attributetitle;
                            }
                            else
                            {
                                $tblTemplate_Config->title = $file;
                            }
							
							$tblTemplate_Config->params = $defaultvalues;
							$tblTemplate_Config->prediction_id = $prediction_id;
							/*
							// Make sure the item is valid
							if (!$tblTemplate_Config->check())
							{
								$this->setError($this->_db->getErrorMsg());
								return false;
							}
					    */
							// Store the item to the database
							if (!$tblTemplate_Config->store())
							{
								$this->setError($this->_db->getErrorMsg());
								return false;
							}
							array_push($records,$template);
							
							/*
              //template not present, create a row with default values
							$params = new JLParameter(null, $xmldir . DS . $file);

							//get the values
							$defaultvalues = array();
							foreach ($params->getGroups() as $key => $group)
							{
								foreach ($params->getParams('params',$key) as $param)
								{
									$defaultvalues[] = $param[5] . '=' . $param[4];
								}
							}
							$defaultvalues = implode('\n', $defaultvalues);

							$title = JText::_($params->name);
							$query =	"	INSERT INTO #__joomleague_prediction_template (title, prediction_id, template, params)
											VALUES ( '$title', '$prediction_id', '$template', '$defaultvalues' )";

							$this->_db->setQuery($query);
							//echo error, allows to check if there is a mistake in the template file
							if (!$this->_db->query())
							{
								$this->setError($this->_db->getErrorMsg());
								return false;
							}
							array_push($records,$template);
							*/
						}
					}
				}
				closedir($handle);
			}
		}
	}

}
?>