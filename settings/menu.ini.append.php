<?php /* #?ini charset="utf8"?

# Add the forum section in admin
[TopAdminMenu]
Tabs[]=forum

# Configure the forum tab link in admin
[Topmenu_forum]
URL[]
URL[default]=content/view/full/__FORUM_NODE_ID__
URL[browse]=content/browse/__FORUM_NODE_ID__
NavigationPartIdentifier=ezforumnavigationpart
Name=Forum
Tooltip=Manage the forum of the site.
Enabled[]
Enabled[default]=true
Enabled[browse]=true
Enabled[edit]=false
Shown[]
Shown[default]=true
Shown[navigation]=true
Shown[browse]=true
PolicyList[]=__FORUM_NODE_ID__

# Configure the left menu in forum section
[Leftmenu_forum]
Name=forum
Links[]
LinkNames[]
Links[look_and_feel]=content/edit/54
PolicyList_look_and_feel[]=56
Links[toolbar_management]=visual/toolbarlist
PolicyList_toolbar_management[]=visual/toolbarlist

# Add the forum section navigation part
[NavigationPart]
Part[ezforumnavigationpart]=Forum

*/

?>