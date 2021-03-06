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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


/**
 * sportsmanagementViewPredictionEntry
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewPredictionEntry extends sportsmanagementView
{

	
	/**
	 * sportsmanagementViewPredictionEntry::init()
	 * 
	 * @return void
	 */
	function init()
	{
   
    $this->headertitle = JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_SECTION_TITLE');
		$this->predictionGame = sportsmanagementModelPrediction::getPredictionGame();
       
		if (isset($this->predictionGame))
		{
			$config	= sportsmanagementModelPrediction::getPredictionTemplateConfig($this->getName());
			$overallConfig = sportsmanagementModelPrediction::getPredictionOverallConfig();

			$this->config = array_merge($overallConfig,$config);
      $configavatar	= sportsmanagementModelPrediction::getPredictionTemplateConfig('predictionusers');
      $this->configavatar = $configavatar;
			$this->predictionMember = sportsmanagementModelPrediction::getPredictionMember($configavatar);
			$this->predictionProjectS = sportsmanagementModelPrediction::getPredictionProjectS();
			$this->actJoomlaUser = JFactory::getUser();
			
            $this->allowedAdmin = sportsmanagementModelPrediction::getAllowed();

			$this->isPredictionMember = sportsmanagementModelPrediction::checkPredictionMembership();
			$this->isNotApprovedMember = sportsmanagementModelPrediction::checkIsNotApprovedPredictionMember();
			$this->isNewMember = $this->model->newMemberCheck();
			$this->tippEntryDone = $this->model->tippEntryDoneCheck();
            
if(version_compare(JVERSION,'3.0.0','ge')) 
        {
            $this->websiteName = JFactory::getConfig()->get('config.sitename');
            }
            else
            {
			$this->websiteName = JFactory::getConfig()->getValue('config.sitename');
            }

			if ( $this->allowedAdmin )
			{
				$lists = array();
				if ( $this->predictionMember->pmID > 0 )
                {
                    $dMemberID = (int)$this->predictionMember->pmID;
                    }
                    else
                    {
                        $dMemberID = 0;
                        }
                        
				$predictionMembers[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_PRED_SELECT_MEMBER'),'value','text');
				if ( $res = sportsmanagementModelPrediction::getPredictionMemberList($this->config) )
                {
                    $predictionMembers = array_merge($predictionMembers,$res);
                    }
                    
				$lists['predictionMembers'] = JHTML::_('select.genericList',$predictionMembers,'uid','class="inputbox" onchange="this.form.submit(); "','value','text',$dMemberID);
				unset($res);
				unset($predictionMembers);
				$this->lists = $lists;
			}

      $this->show_debug_info = JComponentHelper::getParams('com_sportsmanagement')->get('show_debug_info',0);
			// Set page title
			$pageTitle = JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_TITLE');

			$this->document->setTitle($pageTitle);

		}
		else
		{
			JError::raiseNotice(500,JText::_('COM_SPORTSMANAGEMENT_PRED_PREDICTION_NOT_EXISTING'));
		}
	}
	
	/**
	 * sportsmanagementViewPredictionEntry::createStandardTippSelect()
	 * 
	 * @param mixed $tipp_home
	 * @param mixed $tipp_away
	 * @param mixed $tipp
	 * @param string $pid
	 * @param string $mid
	 * @param mixed $seperator
	 * @param mixed $allow
	 * @return
	 */
	function createStandardTippSelect($tipp_home=NULL,$tipp_away=NULL,$tipp=NULL,$pid='0',$mid='0',$seperator,$allow)
	{
		if (!$allow){
			$disabled=' disabled="disabled" ';
			$css = "readonly";
		} else {
			$disabled='';
			$css = "inputbox";
		}
		$output = '';
		$output .= '<input type="hidden" name="tipps[' . $pid . '][' . $mid . ']" value="' . $tipp . '" />';
		$output .= '<input name="homes[' . $pid . '][' . $mid . ']" class="'.$css.'" style="text-align:center; " size="2" value="' . $tipp_home . '" tabindex="1" type="text" ' . $disabled . '/>';
		$output .= ' <b>' . $seperator . '</b> ';
		$output .= '<input name="aways[' . $pid . '][' . $mid . ']" class="'.$css.'" style="text-align:center; " size="2" value="' . $tipp_away . '" tabindex="1" type="text" ' . $disabled . '/>';
		if (!$allow)
		{
			$output .= '<input type="hidden" name="homes[' . $pid . '][' . $mid . ']" value="' . $tipp_home . '" />';
			$output .= '<input type="hidden" name="aways[' . $pid . '][' . $mid . ']" value="' . $tipp_away . '" />';
		}
		return $output;
	}

	/**
	 * sportsmanagementViewPredictionEntry::createTotoTippSelect()
	 * 
	 * @param mixed $tipp_home
	 * @param mixed $tipp_away
	 * @param mixed $tipp
	 * @param string $pid
	 * @param string $mid
	 * @param mixed $allow
	 * @return
	 */
	function createTotoTippSelect($tipp_home=NULL,$tipp_away=NULL,$tipp=NULL,$pid='0',$mid='0',$allow)
	{
		
if ( $this->debuginfo )
{
echo 'tipp_home -> ' . $tipp_home. '<br>';
echo 'tipp_away -> ' . $tipp_away. '<br>';
echo 'tipp -> ' . $tipp. '<br>';
echo 'pid -> ' . $pid. '<br>';
echo 'mid -> ' . $mid. '<br>';
echo 'allow -> ' . $allow. '<br>';
}
    
    
    if (!$allow){$disabled=' disabled="disabled" ';}else{$disabled='';}
		$output = '';
		$output .= '<input type="hidden" name="homes[' . $pid . '][' . $mid . ']" value="' . $tipp_home . '" />';
		$output .= '<input type="hidden" name="aways[' . $pid . '][' . $mid . ']" value="' . $tipp_away . '" />';
		$outputArray = array	(
									JHTML::_('select.option','',JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_NO_TIPP'),'id','name'),
									JHTML::_('select.option','1',JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_HOME_WIN'),'id','name'),
									JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_DRAW'),'id','name'),
									JHTML::_('select.option','2',JText::_('COM_SPORTSMANAGEMENT_PRED_ENTRY_AWAY_WIN'),'id','name')
								);
		$output .= JHTML::_('select.genericlist',$outputArray,'tipps['.$pid.']['.$mid.']','class="inputbox" size="1" ' . $disabled,'id','name',$tipp);
		unset($outputArray);
		if (!$allow)
		{
			$output .= '<input type="hidden" name="tipps['.$pid.']['.$mid.']" value="' . $tipp . '" />';
		}
		return $output;
	}

}
?>
