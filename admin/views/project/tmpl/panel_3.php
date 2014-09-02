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
$templatesToLoad = array('footer','listheader');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);

?>

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

<div id="jsm" class="admin override">

<div id="j-main-container" class="span10">
<section class="content-block" role="main">

<div class="row-fluid">
<div class="span7">

<div class="well well-small">        

<div id="dashboard-icons" class="btn-group">

<a class="btn" href="index.php?option=com_sportsmanagement&task=project.edit&id=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/projekte.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_PSETTINGS') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_PSETTINGS') ?></span>
</a>

<a class="btn" href="index.php?option=com_sportsmanagement&view=templates&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/templates.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_FES') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_FES') ?></span>
</a>

<?php
if ( $this->project->project_art_id != 3 )
{
?>
<a class="btn" href="index.php?option=com_sportsmanagement&view=projectpositions&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/positionen.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_POSITIONS') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_POSITIONS') ?></span>
</a>
<?PHP
}
?>

<a class="btn" href="index.php?option=com_sportsmanagement&view=projectreferees&persontype=3&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/projektschiedsrichter.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_REFEREES') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_REFEREES') ?></span>
</a>

<a class="btn" href="index.php?option=com_sportsmanagement&view=projectteams&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/mannschaften.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_TEAMS') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_TEAMS') ?></span>
</a>

<a class="btn" href="index.php?option=com_sportsmanagement&view=rounds&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/spieltage.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_MATCHDAYS') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_MATCHDAYS') ?></span>
</a>

<a class="btn" href="index.php?option=com_sportsmanagement&view=jlxmlexports&pid=<?PHP echo $this->project->id; ?>">
<img src="components/com_sportsmanagement/assets/icons/xmlexport.png" alt="<?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_XML_EXPORT') ?>" /><br />
<span><?php echo JText::_('COM_SPORTSMANAGEMENT_P_PANEL_XML_EXPORT') ?></span>
</a>

        
</div>        
</div>
</div>

<div class="span5">
					<div class="well well-small">
						<div class="center">
							<img src="components/com_sportsmanagement/assets/icons/boxklein.png" />
						</div>
						<hr class="hr-condensed">
						<dl class="dl-horizontal">
							<dt><?php echo JText::_('COM_SPORTSMANAGEMENT_VERSION') ?>:</dt>
							<dd><?php echo JText::sprintf( '%1$s', sportsmanagementHelper::getVersion() ); ?></dd>
                            
							<dt><?php echo JText::_('COM_SPORTSMANAGEMENT_DEVELOPERS') ?>:</dt>
							<dd><?php echo JText::_('COM_SPORTSMANAGEMENT_DEVELOPER_TEAM'); ?></dd>

							
                            <dt><?php echo JText::_('COM_SPORTSMANAGEMENT_SITE_LINK') ?>:</dt>
							<dd><a href="http://www.fussballineuropa.de" target="_blank">fussballineuropa</a></dd>
							
                            <dt><?php echo JText::_('COM_SPORTSMANAGEMENT_COPYRIGHT') ?>:</dt>
							<dd>&copy; 2014 fussballineuropa, All rights reserved.</dd>
							
                            <dt><?php echo JText::_('COM_SPORTSMANAGEMENT_LICENSE') ?>:</dt>
							<dd>GNU General Public License</dd>
						</dl>
					</div>

					

				</div>
                

</div>
</section>
</div>
</div>


