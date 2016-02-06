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

// no direct access
defined('_JEXEC') or die('Restricted access');
$display = ($params->get('update_module') == 1) ? 'block' : 'none';

?>

<script type="text/javascript">
jlcinjectcontainer['<?php echo $module->id ?>'] = '<?php echo $inject_container ?>';
jlcmodal['<?php echo $module->id ?>'] = '<?php echo $lightbox ?>';
var calendar_baseurl = '<?php echo JUri::base() ?>';
<?PHP
if ($lightbox ==1 && (!isset($_GET['format']) OR ($_GET['format'] != 'pdf'))) 
{
?>
      window.addEvent('domready', function() {
          $$('a.jlcmodal<?php echo $module->id ?>').each(function(el) {
            el.addEvent('click', function(e) {
              new Event(e).stop();
              SqueezeBox.fromElement(el);
            });
          });
      });
<?PHP
}
?>

</script>

<!-- Trigger the modal with a button -->
<!--
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal<?php echo $module->id;?>">Open Modal</button>
-->

<!-- Modal -->
<div id="myModal<?php echo $module->id;?>" class="modal fade" role="dialog">
  <div id="myModaldialog<?php echo $module->id;?>" class="modal-dialog">

    <!-- Modal content-->
    <div id="myModalcontent<?php echo $module->id;?>" class="modal-content">
      <div id="myModalheader<?php echo $module->id;?>" class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div id="myModalbody<?php echo $module->id;?>" class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div id="myModalfooter<?php echo $module->id;?>" class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div id="<?php echo $inject_container ?>">

</div>
<?php if(isset($calendar['calendar'])) { ?>
<div id="jlccalendar-<?php echo $module->id ?>">
<!--jlccalendar-<?php echo $module->id?> start-->

<?php echo $calendar['calendar'] ?> <?php } ?> <?php if (count($calendar['teamslist']) > 0) { ?>
<div style="margin: 0 auto;"><?php
echo JHtml::_('select.genericlist', $calendar['teamslist'], 'jlcteam'.$module->id, 'class="inputbox" style="width:100%;visibility:show;" size="1" onchange="jlcnewDate('.$month.','.$year.','.$module->id.');"',  'value', 'text', JRequest::getVar('jlcteam',0,'default','POST'));
?>
</div>
<?php
}
?> <?php if(isset($calendar['list'])) { ?>
<div>

<div style="display: none;">
<div id="jlCalList-<?php echo $module->id;?>_temp"
	style="overflow: auto; margin: 10px;"></div>
</div>

<?php
//echo $calendar['list'];
$cnt = 0;
for ($x=0;$x < count($calendar['list']);$x++){
	$row = $calendar['list'][$x];
	if(isset($row['tag'])) {
		switch ($row['tag']) {
			case 'span':
				?> <span id="<?php echo $row['divid'];?>"
	class="<?php echo $row['class'];?>"><?php echo $row['text'];?></span><?php
	break;
case 'div':
	?>
<div id="<?php echo $row['divid'];?>"
	class="<?php echo $row['class'];?>"><?php
	break;
case 'table':
	?>
<table style="margin: 0 auto; min-width: 60%;" cellspacing="0"
	cellpadding="0" class="<?php echo $row['class'];?>">
	<?php
	break;
case 'divend':
	?>
	</div>
	<?php
	break;
case 'tableend':
	?>
</table>
	<?php
	break;
case 'headingrow':
	?>
<tr>
	<td class="sectiontableheader jlcal_heading" colspan="5"><?php echo $row['text'];?></td>
</tr>
	<?php
	break;
		}
	}
	else {
		$sclass = ($cnt%2) ? 'sectiontableentry1' : 'sectiontableentry2';
//		$date = JHtml::date ( $row['date'] .' UTC', $params->get('dateformat'), $params->get('time_zone'));
//		$time = JHtml::date ( $row['date'] .' UTC', $params->get('timeformat'), $params->get('time_zone'));
        
        $date = JHtml::date($row['timestamp'] , $params->get('dateformat') );
        //$time = JHtml::date($row['timestamp'] , $params->get('timeformat') );

/**
* testausgabe
*/        
//        $time2 = JHtml::date($row['date'] , $params->get('timeformat') );
        
//        echo 'strtotime   '.JFactory::getDate(strtotime($row['date'])).'<br>';
//        echo 'getTimestamp   '.sportsmanagementHelper::getTimestamp(JFactory::getDate(strtotime($row['date'])));
//        echo 'row timestamp '. $row['timestamp'].'<br>';
        
        $uhrzeit = date("H:i",$row['timestamp']);
        $time = date("H:i",$row['timestamp']);
//        echo " - ",$uhrzeit," Uhr".'<br>';
//        echo 'row datum '. $row['date'].'<br>';
//        echo 'datum '.$date.'<br>';
//        echo 'uhrzeit '.$time.'<br>';
//        echo 'uhrzeit-2 '.$time2.'<br>';
        
		switch ($row['type']) {
			case 'jevents':
				$style = ($row['color'] != '') ? ' style="border-left:4px '.$row['color'].' solid;"' : '';
				?>
<tr class="<?php echo $sclass;?> jlcal_matchrow">
	<td class="jlcal_jevents" colspan="5" <?php echo $style;?>><?php
	if ($row['time'] != '') { ?> <span class="jlcal_jevents_time"><?php echo $row['time'].': ';?></span>
	<?php } ?> <span class="jlcal_jevents_title"><a
		href="<?php echo $row['link'];?>"><?php echo $row['title'];?></a></span>
		<?php
		if ($row['location'] != '') { ?> - <span
		class="jlcal_jevents_location"><?php echo $row['location'];?></span> <?php } ?>
	</td>
</tr>
		<?php
		break;

		// joomleague birthday
case 'jlb':
	?>
<tr class="<?php echo $sclass;?> jlcal_matchrow">
	<td class="jlcal_birthday" colspan="5"><?php
	if (!empty($row['image'])) { echo $row['image']; } ?> <span
		class="jlc_player_name"><?php 
		if (!empty($row['link'])) { ?> <a href="<?php echo $row['link'];?>"
		title="<?php echo $row['link'];?>"> <?php } 
		echo $row['name'];
		if (!empty($row['link'])) { ?> </a> <?php } ?></span> <span
		class="jlc_player_age"><?php echo $row['age'];?></span>
	</td>
</tr>
		<?php
		break;
default:
	?>
<tr class="<?php echo $sclass;?> jlcal_matchrow">
	<td class="jlcal_matchdate"><?php 
	// link to matchdetails
	if (!empty($row['link']))
	{
		?> <a href="<?php echo $row['link'];?>"
		title="<?php echo $row['link'];?>"> <?php
		echo $time;
		?> </a> <?php
	}
	else
	{
		echo $time;
	}
	?></td>
	<td class="jlcal_hometeam"><?php echo $row['homepic'].$row['homename'];?></td>
	<td class="jlcal_teamseperator">-</td>
	<td class="jlcal_awayteam"><?php echo $row['awaypic'].$row['awayname'];;?></td>
	<td class="jlcal_result"><?php echo $row['result'];?></td>
</tr>
	<?php
	break;

		}
	}
}
?>
</div>
<div style="display:<?php echo $display;?>;">
<div id="jlCalList-<?php echo $module->id;?>" style="overflow: auto;"></div>
</div>
<?php
}
?>
<!--jlccalendar-<?php echo $module->id?> end-->
</div>
<div id="jlcTestlist-<?php echo $module->id;?>">
</div>


<?php
if($ajax && $ajaxmod==$module->id){ exit(); } ?>