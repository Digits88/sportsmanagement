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

defined( '_JEXEC' ) or die( 'Restricted access' );

if(version_compare(JVERSION,'3.0.0','ge')) 
{
// Joomla! 3.0 code here
$idxTab = 1;
$view = Jrequest::getCmd('view');
			
foreach ($this->output as $key => $templ) 
{
if ( $idxTab == 1 )
{
echo JHtml::_('bootstrap.startTabSet', $view, array('active'=>'panel'.$idxTab));
}
echo JHtml::_('bootstrap.addTab', $view, 'panel'.$idxTab++, JText::_($key));
echo $this->loadTemplate($templ);
echo JHtml::_('bootstrap.endTab');
}
echo JHtml::_('bootstrap.endTabSet');
        
}
elseif(version_compare(JVERSION,'2.5.0','ge')) 
{
// Joomla! 2.5 code here
?>

<div class="panel with-nav-tabs panel-default">
<div class="panel-heading">

<!-- Tabs-Navs -->
<ul class="nav nav-tabs" >
<?PHP
$count = 0;
$active = 'active';
foreach ($this->output as $key => $templ)
{
if ( $count > 0 )
{
$active = '';
}
?>  
<li class="<?PHP echo $active; ?>"><a href="#<?PHP echo $templ; ?>" data-toggle="tab"><?PHP echo JText::_($key); ?></a></li>
<?PHP
$count++;
}
?>
</ul>
</div>
<!-- Tab-Inhalte -->
<div class="panel-body">
<div class="tab-content">
<?PHP
$count = 0;
$active = 'in active';
foreach ($this->output as $key => $templ)
{
if ( $count )
{
$active = '';
}
?>
<div class="tab-pane fade <?PHP echo $active; ?>" id="<?PHP echo $templ; ?>">
<?PHP   
switch ($templ)
{
    case 'previousx':
    $this->currentteam = $this->match->projectteam1_id;
echo $this->loadTemplate($templ);
$this->currentteam = $this->match->projectteam2_id;
echo $this->loadTemplate($templ);
    break;
    default:
    echo $this->loadTemplate($templ);
    break;
}  
?>
</div>
<?PHP
}
?>
</div>
</div>
</div>
<?PHP
} 
elseif(version_compare(JVERSION,'1.7.0','ge')) 
{
// Joomla! 1.7 code here
} 
elseif(version_compare(JVERSION,'1.6.0','ge')) 
{
// Joomla! 1.6 code here
} 
else 
{
// Joomla! 1.5 code here
}
?>