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

defined('_JEXEC') or die('Restricted access');

//echo 'entry<br /><pre>~' . print_r($this->model->predictionProject,true) . '~</pre><br />';
//echo 'rounds<br /><pre>~' . print_r($this->model->_projectRoundsCount,true) . '~</pre><br />';

?>
<?php
if (!empty($this->model->_projectRoundsCount))
{
	?><br />
  <table width='96%' align='center' cellpadding='0' cellspacing='0' border='0'>
  <tr>
  <td>
  <div class='pagenav'>
  <?php
	/*
	 * echo $this->model->pagenav(	$this->model->predictionProject->prediction_id,
								$this->model->_projectRoundsCount,
								$this->roundID,
								$this->model->predictionProject->project_id);
	*/							
				$pageNavigation  = "<div class='pagenav'>";
				$pageNavigation .= JoomleaguePagination::pagenav($this->model->getPredictionProject($this->model->predictionProject->project_id));
				$pageNavigation .= "</div>";
				echo $pageNavigation;
			  
		
	?>
  </div>
  </td>
  </tr>
  </table>
  <?php
}
?>