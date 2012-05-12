<?php /* #?ini charset="utf8"?

#Configure the admininterface navigation part
#In forum section, configure the default subelements list table
[SubItems]
VisibleColumns[ezforumnavigationpart]=checkbox;crank;name;creator;published_date

#Define a new type of tab for content/view in backend
[WindowControlsSettings]
AdditionalTabs[]=lasttopics

#Configure the tab last topics in backend content/view
[AdditionalTab_lasttopics]
Title=Last Topics
Description=The last 10 topics activities in the forum
NavigationPartName=ezforumnavigationpart
HeaderTemplate=lasttopics_header.tpl
Template=lasttopics.tpl

*/

?>