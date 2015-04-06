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

switch($this->fieldset)
{
case 'training':
$view = JRequest::getCmd('view', 'cpanel');
?>                
                        <fieldset class="adminform">
                                
                                <table class='admintable'>
                                        <tr>
                                                <td class='key' nowrap='nowrap'>
                                                        <?php echo JText::_('JACTION_CREATE'); ?>&nbsp;<input type='checkbox' name='add_trainingData' id='add' value='1' onchange='javascript:submitbutton("<?php echo $view; ?>.apply");' />
                                                </td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_DAY'); ?></td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_STARTTIME'); ?></td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_ENDTIME'); ?></td>
                                                <td class='key' style='text-align:center;'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_PLACE'); ?></td>
                                                <td class='key' style='text-align:center;'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_NOTES'); ?></td>
                                        </tr>
                                        <?php
                                        if (!empty($this->trainingData))
                                        {
                                                ?>
                                                <input type='hidden' name='tdCount' value='<?php echo count($this->trainingData); ?>' />
                                                <?php
                                                foreach ($this->trainingData AS $td)
                                                {
                                                        $hours=($td->time_start / 3600); $hours=(int)$hours;
                                                        $mins=(($td->time_start - (3600*$hours)) / 60); $mins=(int)$mins;
                                                        $startTime=sprintf('%02d',$hours).':'.sprintf('%02d',$mins);
                                                        $hours=($td->time_end / 3600); $hours=(int)$hours;
                                                        $mins=(($td->time_end - (3600*$hours)) / 60); $mins=(int)$mins;
                                                        $endTime=sprintf('%02d',$hours).':'.sprintf('%02d',$mins);
                                                        ?>
                                                        <tr>
                                                                <td class='key' nowrap='nowrap'>
                                                                        <?php echo JText::_('JACTION_DELETE');?>&nbsp;<input type='checkbox' name='delete[]' value='<?php echo $td->id; ?>' onchange='javascript:submitbutton("<?php echo $view; ?>.apply");' />
                                                                </td>
                                                                <td nowrap='nowrap' width='5%'><?php echo $this->lists['dayOfWeek'][$td->id]; ?></td>
                                                                <td nowrap='nowrap' width='5%'>
                                                                        <input class='text' type='text' name='time_start[<?php echo $td->id; ?>]' size='8' maxlength='5' value='<?php echo $startTime; ?>' />
                                                                </td>
                                                                <td nowrap='nowrap' width='5%'>
                                                                        <input class='text' type='text' name='time_end[<?php echo $td->id; ?>]' size='8' maxlength='5' value='<?php echo $endTime; ?>' />
                                                                </td>
                                                                <td>
                                                                        <input class='text' type='text' name='place[<?php echo $td->id; ?>]' size='40' maxlength='255' value='<?php echo $td->place; ?>' />
                                                                </td>
                                                                <td>
                                                                        <textarea class='text_area' name='notes[<?php echo $td->id; ?>]' rows='3' cols='40' value='' /><?php echo $td->notes; ?></textarea>
                                                                        <input type='hidden' name='tdids[]' value='<?php echo $td->id; ?>' />
                                                                </td>
                                                        </tr>
                                                        <?php
                                                }
                                        }
                                        ?>
                                </table>
                        </fieldset>
<?PHP
break;

case 'help':
?>
<fieldset class='adminform'>
							
						<?php
						echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_PGAME_HINT_1');
						?>
						</fieldset>
<?php                        
break;
// f�r mannschaften des vereines
case 'teamsofclub':
if ( isset($this->teamsofclub) )
{
   
   
?>
<fieldset class="adminform">
<table>
<?php
foreach ( $this->teamsofclub as $team )
{
?>
			<tr>
				<td>
					<input type="hidden" name="team_id[]" value="<?php echo $team->id;?>" />
                    <input type="text" name="team_value_id[]" size='50' maxlength='100' value="<?php echo $team->name;?>" />
				</td>
			</tr>
			<?php    
    
}
?>
</table>
</fieldset>
<?PHP
} 
break;


// f�r extra felder
case 'extra_fields':
?>
	<fieldset class="adminform">
	
	<table>
    <?php
    if ( $this->lists )
    {
	for($p=0;$p<count($this->lists['ext_fields']);$p++)
            {
			?>
			<tr>
				<td width="100">
					<?php echo $this->lists['ext_fields'][$p]->name;?>
				</td>
				<td>
					<textarea name="extraf[]" cols="100" rows="4"><?php echo isset($this->lists['ext_fields'][$p]->fvalue)?htmlspecialchars($this->lists['ext_fields'][$p]->fvalue):""?></textarea>
					<input type="hidden" name="extra_id[]" value="<?php echo $this->lists['ext_fields'][$p]->id?>" />
                    <input type="hidden" name="extra_value_id[]" value="<?php echo $this->lists['ext_fields'][$p]->value_id?>" />
				</td>
			</tr>
			<?php	
			}
            }
	?>
    </table>
	</fieldset>
	<?php

break;

// f�r google maps    
case 'maps':
?>
<style type="text/css">
<!--
/* die Map */			
			#map {
				height: 350px;
				width: 600px;
				margin: 20px auto;
				//border: 5px solid #969696;
			}
-->
</style>

<script type="text/javascript">
jQuery(function(){ // document.ready
// Map initialisieren	 
				jQuery("#map").gmap3(
//				{
//				action: 'init',    
//				options: {
//					streetViewControl: true,
//					}    
//				}    
				);


                
});

setTimeout(function(){
  jQuery('#map')
    .width("600px")
    .height("350px") 
    .gmap3({trigger:"resize"});
}, 4000);

</script>

<div id="map" class="ui-widget ui-corner-all ui-helper-clearfix"></div>
<?PHP
break;

// f�r google maps    
case 'maps1':
$plugin = JPluginHelper::getPlugin('system', 'plugin_googlemap3');
$paramsPlugin = new JRegistry($plugin->params);

$arrPluginParams = array();

$arrPluginParams[] = "mapType='".$paramsPlugin->get('mapType','')."'";
$arrPluginParams[] = "zoomWheel='".$paramsPlugin->get('zoomWheel','')."'";
$arrPluginParams[] = "zoom='".$paramsPlugin->get('zoom','')."'";
$arrPluginParams[] = "corzoom='".$paramsPlugin->get('corzoom','')."'";
$arrPluginParams[] = "minzoom='".$paramsPlugin->get('minzoom','')."'";
$arrPluginParams[] = "maxzoom='".$paramsPlugin->get('maxzoom','')."'";
$arrPluginParams[] = "showEarthMaptype='".$paramsPlugin->get('showEarthMaptype','')."'";

//$arrPluginParams[] = "kml='".$this->kmlpath."'";
$arrPluginParams[] = "kmlrenderer='".$paramsPlugin->get('kmlrenderer','')."'";
$arrPluginParams[] = "kmlsidebar='".$paramsPlugin->get('kmlsidebar','')."'";
$arrPluginParams[] = "kmlsbwidth='".$paramsPlugin->get('kmlsbwidth','')."'";
$arrPluginParams[] = "overview='1'";
$arrPluginParams[] = "lightbox='1'";

$arrPluginParams[] = "width='".$paramsPlugin->get('width','')."'";
$arrPluginParams[] = "height='".$paramsPlugin->get('height','')."'";

/*
$params  = "{mosmap mapType='".$paramsPlugin->get('mapType','')."'|dir='1'|zoomWheel='1'|zoom='".$paramsPlugin->get('zoom','')."'|corzoom='0'|minzoom='0'|maxzoom='19'|
showEarthMaptype='1'|
showNormalMaptype='1' |showSatelliteMaptype='1' |showTerrainMaptype='1' |showHybridMaptype='1'   
|kml=''|kmlrenderer='geoxml'|controltype='user'|kmlsidebar='left'|kmlsbwidth='200'|
lightbox='1'|
width='".$paramsPlugin->get('width','')."'|height='".$paramsPlugin->get('height','')."' |overview='1'  }";  
*/

$params  = "{mosmap width='500'\|height='400'\|lat='52.052312'\|lon='4.447141'\|
zoom='3'\|mapType='Satellite'\|text='sv DWO'\|tooltip='DWO'\|
marker='1'\|align='center' } ";
//$params  = "{mosmap mapType='".$paramsPlugin->get('mapType','')."'}";  
echo JHtml::_('content.prepare', $params);

break;

// f�r google maps    
case 'maps2':
$document = JFactory::getDocument();
$document->addScript('http://maps.google.com/maps/api/js?&sensor=true');
//$document->addScript('https://maps.googleapis.com/maps/api/js?v=3.exp');
?>
<script language="javascript" type="text/javascript">
var map;

function initialize() {
	var start = new google.maps.LatLng(<?php echo $this->item->latitude?>,<?php echo $this->item->longitude?>);
 	var image = 'http://maps.google.com/mapfiles/kml/pal2/icon49.png';
     var myOptions = {
      zoom: 12,
      center: start,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    //map = new google.maps.Map($('map'), myOptions);
    map = new google.maps.Map(document.getElementById('map'),myOptions);
    var marker = new google.maps.Marker({
      position: start,
      map: map,
      icon: image,
      title: '<?php echo $this->item->name?>'
  });
    
    kartenwerte();
	}
	
    //google.maps.event.addDomListener(window, 'load', initialize);
    //google.maps.event.trigger(map,'resize');
    
	function kartenwerte() {
	var mapcenter =  map.getCenter();
	$('conf_center_lat').value =mapcenter.lat();
	$('conf_center_lng').value =mapcenter.lng();	
	$('conf_start_zoom').value = map.getZoom();
	
	} 
    
//    $(document).ready(function() {
//  google.maps.event.trigger(map, 'resize');
//          });
          
</script>

<fieldset class="adminform">
			
<body onLoad="initialize()">             

<div id="map" style="width:400px; height:400px;"></div>
</fieldset>
<?PHP
break;

// f�r die extended daten
case 'extended':
if ( isset($this->extended) )
{
foreach ($this->extended->getFieldsets() as $fieldset)
{
	?>
	<fieldset class="adminform">
	
	<?php
	$fields = $this->extended->getFieldset($fieldset->name);
	
	if(!count($fields)) {
		echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
	}
	
	foreach ($fields as $field)
	{
	   if ( COM_SPORTSMANAGEMENT_JOOMLAVERSION == '2.5' )
        {
		echo $field->label;
       	echo $field->input;
        }
        else
        {
            ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php
        }
	}
	?>
	</fieldset>
	<?php
}
}
else
{
    echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
}
break;

// f�r die extended daten
case 'extendeduser':
if ( isset($this->extendeduser) )
{
foreach ($this->extendeduser->getFieldsets() as $fieldset)
{
	?>
	<fieldset class="adminform">
	
	<?php
	$fields = $this->extendeduser->getFieldset($fieldset->name);
	
	if(!count($fields)) {
		echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
	}
	
	foreach ($fields as $field)
	{
		echo $field->label;
       	echo $field->input;
	}
	?>
	</fieldset>
	<?php
}
}
else
{
    echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
}
break;

// f�r die extended daten
case 'params':
if ( isset($this->formparams) )
{
foreach ($this->formparams->getFieldsets() as $fieldset)
{
	?>
	<fieldset class="adminform">
	
	<?php
	$fields = $this->formparams->getFieldset($fieldset->name);
	
	if(!count($fields)) 
    {
		echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
	}
	echo '<b><p class="tab-description">'.JText::_($this->description).'</p></b>';
	foreach ($fields as $field)
	{
		echo $field->label;
       	echo $field->input;
	}
	?>
	</fieldset>
	<?php
}
}
else
{
    echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
}
break;

// das ist der standard
default:
?>
		<fieldset class="adminform">
			<table class="admintable">
					<?php 
                    foreach ($this->form->getFieldset($this->fieldset) as $field): 
                    //echo 'name -><pre> '.print_r($field->getFieldAttribute(),true).'</pre>';
                    ?>
					<tr>
						<td class="key"><?php echo $field->label; ?></td>
						<td><?php echo $field->input; ?></td>
                        <td>
                        <?PHP
                        $suchmuster = array ("jform[","]","request[");
                $ersetzen = array ('', '', '');
                $var_onlinehelp = str_replace($suchmuster, $ersetzen, $field->name);
                
                switch ($var_onlinehelp)
                {
                    case 'id':
                    break;
                    default:
                ?>
                <a	rel="{handler: 'iframe',size: {x: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_WIDTH; ?>,y: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_HEIGHT; ?>}}"
									href="<?php echo COM_SPORTSMANAGEMENT_HELP_SERVER.'SM-Backend-Felder:'.JRequest::getVar( "view").'-'.$var_onlinehelp; ?>"
									 class="modal">
									<?php
									echo JHtml::_(	'image','media/com_sportsmanagement/jl_images/help.png',
													JText::_('COM_SPORTSMANAGEMENT_HELP_LINK'),'title= "' .
													JText::_('COM_SPORTSMANAGEMENT_HELP_LINK').'"');
									?>
								</a>
                
                <?PHP
                break;
                }
                ?> 
                </td>       
					</tr>					
					<?php endforeach; ?>
			</table>
		</fieldset>
<?PHP
break;
}

?>        