/**
Customize the display of Widgets/Sidebars titles to show the restriction imposed by some level
Keeping it global so it can be accessed globally
*/
function membpress_restrict_title_widget(sidebar_id, widget_id, level_name)
{ 
   jQuery('#' + sidebar_id + ' .widget[id$=' + widget_id + '] .widget-title').append('<span class="membpress-in-widget-title">' + level_name + '</span>');
   jQuery('#accordion-section-sidebar-widgets-' + sidebar_id + ' .widget[id$=' + widget_id + '] .widget-title').append('<span class="membpress-in-widget-title">' + level_name + '</span>');	
}