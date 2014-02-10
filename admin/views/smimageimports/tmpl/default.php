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

$templatesToLoad = array('footer');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);

?>
<form action="<?php echo $this->request_url; ?>" method="post" id="adminForm" name="adminForm">

<fieldset class="adminform">
<legend><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_EXT_IMAGES_IMPORT'); ?></legend>

<table class="adminlist">
<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NUM'); ?></th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
                    <th><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_IMPORT_IMAGE'); ?>
                    <th><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_IMPORT_PATH'); ?>
                    <th><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_IMPORT_DIRECTORY'); ?>
                    <th><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_IMPORT_FILE'); ?>

</tr>
</thead>
                    
<?PHP
$k=0;
for ($i=0,$n=count($this->files); $i < $n; $i++)
{
$row =& $this->files[$i];
$checked = JHtml::_('grid.checkedout',$row,$i);
?>
<tr class="<?php echo "row$k"; ?>">
<td class="center"><?php echo ($i +1); ?></td>
<td class="center"><?php echo $checked; ?></td>
<td><?php echo $row->picture; ?></td>
<input type='hidden' name='picture[<?php echo $i; ?>]' value='<?php echo $row->picture; ?>' />
<td><?php echo $row->folder; ?></td>
<input type='hidden' name='folder[<?php echo $i; ?>]' value='<?php echo $row->folder; ?>' />
<td><?php echo $row->directory; ?></td>
<input type='hidden' name='directory[<?php echo $i; ?>]' value='<?php echo $row->directory; ?>' />
<td><?php echo $row->file; ?></td>
<input type='hidden' name='file[<?php echo $i; ?>]' value='<?php echo $row->file; ?>' />
</tr>
<?php
$k=1 - $k;
}

?>

</table>

</fieldset>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token')."\n"; ?>
</form>
<?PHP
echo "<div>";
echo $this->loadTemplate('footer');
echo "</div>";
?>  