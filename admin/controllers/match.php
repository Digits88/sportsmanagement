<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie können es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es nützlich sein wird, aber
* OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 

/**
 * sportsmanagementControllermatch
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementControllermatch extends JControllerForm
{

/**
	 * Class Constructor
	 *
	 * @param	array	$config		An optional associative array of configuration settings.
	 * @return	void
	 * @since	1.5
	 */
	function __construct($config = array())
	{
		parent::__construct($config);

		// Map the apply task to the save method.
		$this->registerTask('apply', 'save');
	}
    
    /**
     * sportsmanagementControllermatch::copyfrom()
     * 
     * @return void
     */
    public function copyfrom()
	{
		$app = JFactory::getApplication();
		$option = JRequest::getCmd('option');
        $db = JFactory::getDbo();
		$msg = '';
		$post = JRequest::get('post');
		$model = $this->getModel('match');
		$add_match_count = $post['add_match_count'];
		$round_id = JRequest::getInt('rid');
		$post['project_id'] = $app->getUserState($option.'.pid',0);
		$post['round_id'] = $round_id;
        $mdlProject = JModelLegacy::getInstance("Project", "sportsmanagementModel");
	    $projectws = $mdlProject->getProject($post['project_id']);
        
        
		//$project_tz = new DateTimeZone($projectws->timezone);
		//$timezone = $projectws->timezone;
        //if ( empty($timezone) )
        //{
//            $timezone = 'Europe/Berlin';
//        }
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' post<br><pre>'.print_r($post,true).'</pre>'),'');
        
		// Add matches (type=1)
		if ( $post['addtype']==1 )
		{
			if ($add_match_count > 0) // Only MassAdd a number of new and empty matches
			{
				if (!empty($post['autoPublish'])) // 1=YES Publish new matches
				{
					$post['published'] = 1;
				}

				$matchNumber = JRequest::getInt('firstMatchNumber',1);
				$roundFound = false;
				
				if ($projectRounds = $model->getProjectRoundCodes($post['project_id']))
				{
					// convert date and time to utc
					//$uiDate = $post['match_date'];
					//$uiTime = $post['startTime'];
					//$post['match_date'] = $this->convertUiDateTimeToMatchDate($uiDate, $uiTime, $timezone);
                    $post['match_date'] = sportsmanagementHelper::convertDate($post['match_date'],0).' '.$post['startTime'];
                    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' match_date<br><pre>'.print_r($post['match_date'],true).'</pre>'),'');
			
					foreach ($projectRounds AS $projectRound)
					{
						if ( $projectRound->id == $post['round_id'] )
                        {
							$roundFound=true;
						}
						if ($roundFound)
						{
							$post['round_id'] = $projectRound->id;
							$post['roundcode'] = $projectRound->roundcode;
							for ($x=1; $x <= $add_match_count; $x++)
							{
								if (!empty($post['firstMatchNumber'])) // 1=YES Add continuous match Number to new matches
								{
									$post['match_number'] = $matchNumber;
								}
                                
                                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' x -> '.$x.'' ),'Error');
                                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' add_match_count -> '.$add_match_count.'' ),'Error');

								//if ($model->store($post))
                                $model = $this->getModel('match');
                                if ($model->save($post))
								{
									//$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ADD_MATCH');
                                    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getErrorMsg<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
									$matchNumber++;
								}
								else
								{
									$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ERROR_ADD_MATCH');
                                    //$msg = JText::sprintf('COM_SPORTSMANAGEMENT_ADMIN_UPDATES_FROM_FILE','<b>'.print_r($db->getErrorMsg(),true).'</b>');
                                    //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getErrorMsg<pre>'.print_r($db->getErrorMsg(),true).'</pre>' ),'Error');
									break;
								}
							}
							if (empty($post['addToRound'])) // 1=YES Add matches to all rounds
							{
								break;
							}
						}
					}
				}
			}
		}
		// Copy matches (type=2)
		if ($post['addtype']==2)// Copy or mirror new matches from a selected existing round
		{
			if ( $matches = $model->getRoundMatches($round_id))
			{
				// convert date and time to utc
				//$uiDate = $post['date'];
				//$uiTime = $post['startTime'];
				//$post['match_date'] = $this->convertUiDateTimeToMatchDate($uiDate, $uiTime, $timezone);
                $post['match_date'] = sportsmanagementHelper::convertDate($post['date'],0).' '.$post['startTime'];

				foreach($matches as $match)
				{
					//aufpassen,was uebernommen werden soll und welche daten durch die aus der post ueberschrieben werden muessen
					//manche daten muessen auf null gesetzt werden

 					$dmatch['match_date'] = $post['match_date'];
					
					if ($post['mirror'] == '1')
					{
						$dmatch['projectteam1_id'] = $match->projectteam2_id;
						$dmatch['projectteam2_id'] = $match->projectteam1_id;
					}
					else
					{
						$dmatch['projectteam1_id'] = $match->projectteam1_id;
						$dmatch['projectteam2_id'] = $match->projectteam2_id;
					}
					$dmatch['project_id'] = $post['project_id'];
					$dmatch['round_id']	= $post['round_id'];
					if ($post['start_match_number'] != '')
					{
						$dmatch['match_number'] = $post['start_match_number'];
						$post['start_match_number']++;
					}

					//if ($model->store($dmatch))
                    $model = $this->getModel('match');
                    if ($model->save($dmatch))
					{
						$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_COPY_MATCH');
					}
					else
					{
						$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ERROR_COPY_MATCH');
					}
				}
			}
			else
			{
				$msg=JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ERROR_COPY_MATCH2');
			}
		}
		//echo $msg;
		$link = 'index.php?option=com_sportsmanagement&view=matches';
		//$link .= '&hidemainmenu='.JRequest::getVar('hidemainmenu',0);
		$this->setRedirect($link,$msg);
	}
    
	/**
	 * sportsmanagementControllermatch::insertgooglecalendar()
	 * 
	 * @return void
	 */
	function insertgooglecalendar()
    {
        $option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
        $model = $this->getModel('match');
        $result = $model->insertgooglecalendar();
        
        if ( $result )
        {
            $msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ADD_GOOGLE_EVENT');
        }
        else
        {
            $msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_NO_GOOGLECALENDAR_ID');
        }
        
        $link = 'index.php?option=com_sportsmanagement&view=matches';
		$this->setRedirect($link,$msg);
    }
    
    
    /**
     * sportsmanagementControllermatch::cancelmassadd()
     * 
     * @return void
     */
    function cancelmassadd()
    {
    $link = 'index.php?option=com_sportsmanagement&view=matches&massadd=0';
	$this->setRedirect($link,$msg);       
    }
    
    /**
     * sportsmanagementControllermatch::massadd()
     * 
     * @return void
     */
    function massadd()
	{
	$link = 'index.php?option=com_sportsmanagement&view=matches&layout=massadd&massadd=1';
	$this->setRedirect($link,$msg);   
    }
       
    /**
	 * Method add a match to round
	 *
	 * @access	public
	 * @return	
	 * @since	0.1
	 */
    function addmatch()
	{
		$option = JRequest::getCmd('option');
		$app = JFactory::getApplication();
		$post = JRequest::get('post');
		$post['project_id'] = $app->getUserState( "$option.pid", '0' );
		$post['round_id'] = $app->getUserState( "$option.rid", '0' );
        $post['count_result'] = 1;
        $post['published'] = 1;
		$model = $this->getModel('match');
        $row = $model->getTable();
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' row<br><pre>'.print_r($row,true).'</pre>'),'');
        
        // bind the form fields to the table
        if (!$row->bind($post)) 
        {
        $this->setError($this->_db->getErrorMsg());
        return false;
        }
        // make sure the record is valid
        if (!$row->check()) 
        {
        $this->setError($this->_db->getErrorMsg());
        return false;
        }
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' row<br><pre>'.print_r($row,true).'</pre>'),'');
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' post<br><pre>'.print_r($post,true).'</pre>'),'');
        
        // store to the database
		//if ($row->store($post))
        if ($row->save($post))
		{
			$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ADD_MATCH');
		}
		else
		{
			$msg = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCH_CTRL_ERROR_ADD_MATCH').$model->getError();
		}
		$link = 'index.php?option=com_sportsmanagement&view=matches';
		$this->setRedirect($link,$msg);
	}
    
    
    /**
     * sportsmanagementControllermatch::save()
     * 
     * @return void
     */
    function save($key = NULL, $urlVar = NULL)
	{
	// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
        $db = sportsmanagementHelper::getDBConnection();
        $id	= JRequest::getInt('id');
//        $tmpl = JRequest::getVar('tmpl');
		$model = $this->getModel('match');
        $data = JRequest::getVar('jform', array(), 'post', 'array');
//        $createTeam = JRequest::getVar('createTeam');
        $return = $model->save($data);   
       
       // Set the redirect based on the task.
		switch ($this->getTask())
		{
			case 'apply':
			$message = JText::_('JLIB_APPLICATION_SAVE_SUCCESS');
			$this->setRedirect('index.php?option=com_sportsmanagement&view=match&layout=edit&tmpl=component&id='.$id, $message);
			break;

			case 'save':
			default:
			$this->setRedirect('index.php?option=com_sportsmanagement&view=close&tmpl=component');
			break;
		}
        
       
    }   
    
    
    /**
	 * Method to remove a matchday
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	function remove()
	{
	$app = JFactory::getApplication();
    $pks = JRequest::getVar('cid', array(), 'post', 'array');
    $model = $this->getModel('match');
    $model->delete($pks);
	
    $this->setRedirect('index.php?option=com_sportsmanagement&view=matches');    
        
   }
   
   
	/**
	 * sportsmanagementControllermatch::picture()
	 * 
	 * @return void
	 */
	function picture()
  {
  //$cid = JRequest::getVar('cid',array(0),'','array');
	$match_id = JRequest::getInt('id',0);
  $dest = JPATH_ROOT.'/images/com_sportsmanagement/database/matchreport/'.$match_id;
  $folder = 'matchreport/'.$match_id;
  //$this->setState('folder', $folder);
  if(JFolder::exists($dest)) {
  }
  else
  {
  JFolder::create($dest);
  }

  $msg=JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_EDIT_MATCHPICTURE');
  $link='index.php?option=com_media&view=images&tmpl=component&asset=com_sportsmanagement&author=&folder=com_sportsmanagement/database/'.$folder;
	$this->setRedirect($link,$msg);
  
  }
  
  /**
   * sportsmanagementControllermatch::readpressebericht()
   * 
   * @return void
   */
  function readpressebericht()
    {
    JRequest::setVar('hidemainmenu',1);
		JRequest::setVar('layout','readpressebericht');
		JRequest::setVar('view','match');
		JRequest::setVar('edit',true);

		
		parent::display();    
        
        
    }
    
    /**
     * sportsmanagementControllermatch::savepressebericht()
     * 
     * @return
     */
    function savepressebericht()
    {
    	// Check for request forgeries
		JRequest::checkToken() or die('COM_SPORTSMANAGEMENT_GLOBAL_INVALID_TOKEN');
		$msg='';
		JToolBarHelper::back(JText::_('JPREV'),JRoute::_('index.php?option=com_sportsmanagement&task=jlxmlimport.display'));
		$app = JFactory::getApplication();
		$post=JRequest::get('post');
        $model = $this->getModel('match');

		// first step - upload
		if (isset($post['sent']) && $post['sent']==1)
		{
			$upload = JRequest::getVar('import_package',null,'files','array');
            //$cid = JRequest::getVar('cid',array(0),'','array');
            $match_id = JRequest::getInt('id',0);
			$tempFilePath = $upload['tmp_name'];
			$app->setUserState('com_sportsmanagement'.'uploadArray',$upload);
			$filename = '';
			$msg = '';
			$dest = JPATH_SITE.DS.'tmp'.DS.$upload['name'];
			$extractdir = JPATH_SITE.DS.'tmp';
			//$importFile = JPATH_SITE.DS.'tmp'. DS.'pressebericht.jlg';
if(!JFolder::exists(JPATH_SITE.DS.'media'.DS.'com_sportsmanagement'.DS.'pressebericht'))
{
JFolder::create(JPATH_SITE.DS.'media'.DS.'com_sportsmanagement'.DS.'pressebericht');
}			
            $importFile = JPATH_SITE.DS.'media'.DS.'com_sportsmanagement'.DS.'pressebericht'.DS.$match_id.'.jlg';
            
			if (JFile::exists($importFile))
			{
				JFile::delete($importFile);
			}
            
			if (JFile::exists($tempFilePath))
			{
					if (JFile::exists($dest))
					{
						JFile::delete($dest);
					}
					if (!JFile::upload($tempFilePath,$dest))
					{
						JError::raiseWarning(500,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_CANT_UPLOAD'));
						return;
					}
					else
					{
						if (strtolower(JFile::getExt($dest))=='zip')
						{
							$result=JArchive::extract($dest,$extractdir);
							if ($result === false)
							{
								JError::raiseWarning(500,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_EXTRACT_ERROR'));
								return false;
							}
							JFile::delete($dest);
							$src=JFolder::files($extractdir,'jlg',false,true);
							if(!count($src))
							{
								JError::raiseWarning(500,'COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_EXTRACT_NOJLG');
								//todo: delete every extracted file / directory
								return false;
							}
							if (strtolower(JFile::getExt($src[0]))=='jlg')
							{
								if (!@ rename($src[0],$importFile))
								{
									JError::raiseWarning(21,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_ERROR_RENAME'));
									return false;
								}
							}
							else
							{
								JError::raiseWarning(500,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_TMP_DELETED'));
								return;
							}
						}
						else
						{
							if (strtolower(JFile::getExt($dest))=='csv')
							{
								if (!@ rename($dest,$importFile))
								{
									JError::raiseWarning(21,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_RENAME_FAILED'));
									return false;
								}
							}
							else
							{
								JError::raiseWarning(21,JText::_('COM_SPORTSMANAGEMENT_ADMIN_XML_IMPORT_CTRL_WRONG_EXTENSION'));
								return false;
							}
						}
					}
			}
		}
        //$csv_file = $model->getPressebericht();  
		$link='index.php?option=com_sportsmanagement&tmpl=component&task=match.readpressebericht&match_id='.$match_id;
		$this->setRedirect($link,$msg);    
        
        
    }
    
  /**
   * sportsmanagementControllermatch::savecsvpressebericht()
   * 
   * @return void
   */
  function savecsvpressebericht()
    {
    JRequest::setVar('hidemainmenu',1);
	JRequest::setVar('layout','savepressebericht');
	JRequest::setVar('view','match');
	JRequest::setVar('edit',true);
	
	parent::display();
    }
        
    /**
     * sportsmanagementControllermatch::pressebericht()
     * 
     * @return void
     */
    function pressebericht()
    {
    JRequest::setVar('hidemainmenu',1);
	JRequest::setVar('layout','pressebericht');
	JRequest::setVar('view','match');
	JRequest::setVar('edit',true);
	
	parent::display();    
        
    }
    
    /**
     * sportsmanagementControllermatch::convertUiDateTimeToMatchDate()
     * 
     * @param mixed $uiDate
     * @param mixed $uiTime
     * @param mixed $timezone
     * @return
     */
    private function convertUiDateTimeToMatchDate($uiDate, $uiTime, $timezone)
	{
		$format = JText::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_DATE_FORMAT');

		if (((!strpos($uiDate,'-')!==false) && (!strpos($uiDate,'.')!==false)) && (strlen($uiDate) <= 8 ))
		{
			// to support short date inputs
			if (strlen($uiDate) == 8 )
			{
				if ($format == 'Y-m-d') 
				{
					// for example 20111231 is used for 31 december 2011
					$dateStr = substr($uiDate,0,4) . '-' . substr($uiDate,4,2) . '-' . substr($uiDate,6,2);
				} 
				elseif ($format == 'd-m-Y')
				{
					// for example 31122011 is used for 31 december 2011
					$dateStr = substr($uiDate,0,2) . '-' . substr($uiDate,2,2) . '-' . substr($uiDate,4,4);
				}
				elseif ($format == 'd.m.Y')
				{
					// for example 31122011 is used for 31 december 2011
					$dateStr = substr($uiDate,0,2) . '.' . substr($uiDate,2,2) . '.' . substr($uiDate,4,4);
				}
			}
			
			elseif (strlen($uiDate) == 6 )
			{
				if ($format == 'Y-m-d') 
				{
					// for example 111231 is used for 31 december 2011
					$dateStr = substr(date('Y'),0,2) . substr($uiDate,0,2) . '-' . substr($uiDate,2,2) . '-' . substr($uiDate,4,2);
				} 
				elseif ($format == 'd-m-Y')
				{
					// for example 311211 is used for 31 december 2011
					$dateStr = substr($uiDate,0,2) . '-' . substr($uiDate,2,2) . '-' . substr(date('Y'),0,2) . substr($uiDate,4,2);
				}
				elseif ($format == 'd.m.Y')
				{
					// for example 311211 is used for 31 december 2011
					$dateStr = substr($uiDate,0,2) . '.' . substr($uiDate,2,2) . '.' . substr(date('Y'),0,2) . substr($uiDate,4,2);
				}
			}
		}
		else
		{
			$dateStr = $uiDate;
		}

		if (!empty($uiTime))
		{
			$format  .= ' H:i';

			if(strpos($uiTime,":")!==false)
			{
				$dateStr .= ' '.$uiTime;
			}
			// to support short time inputs
			// for example 2158 is used instead of 21:58
			elseif (strlen($uiTime) == 4 )
			{
				$dateStr .= ' '.substr($uiTime, 0, -2) . ':' . substr($uiTime, -2);  
			}
			// for example 21 is used instead of 2100
			elseif (strlen($uiTime) == 2 )
			{
				$dateStr .= ' '.$uiTime. ':00';  
			}
		}
		$timestamp = DateTime::createFromFormat($format, $dateStr, $timezone);
		if(is_object($timestamp))  {
			$timestamp->setTimezone(new DateTimeZone('UTC'));
			return $timestamp->format('Y-m-d H:i:s');
		} else {
			return '0000-00-00 00:00:00';
		}
	}
       
    

}
