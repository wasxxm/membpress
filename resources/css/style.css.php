<?php
// load the Wordpress Main Load File
require_once '../../../../../wp-load.php';
header("Content-type: text/css");
?>

/*
Override default #poststuff
*/
.membpress {
	background-color: #045580;
	color: #FFF;
	padding-left: 15px;
	padding-bottom: 15px;
}
.membpress h2, .membpress .membpress_settings_collapse_expand a {
	color: #FFF !important;
}
.membpress .meta-box-sortables {
	color: #2C2C2C !important;
}
.membpress .postbox {
	background-color: #E2F2FA !important;
}
.membpress .postbox .inside p {
	line-height: 1.65em !important;
}
.membpress #poststuff {
	margin-top: 0px;
	padding-top: 0px !important;
}
.membpress #poststuff h2 {
	font-size: 1.7em;
}
.membpress_header {
	text-align: center;
}
.membpress_header img {
	width: 25%;
	padding-top: 15px;
}
/*
remove the move cursor from the handle
*/
.membpress .hndle {
	cursor: pointer !important;
	background-color: #f5fafc;
	border-bottom-color: #bfdae7;
}
/* Increase the left margin of radio boxes in welcome login group */
.membpress .membpress_welcome_login_group input[type=radio] {
	margin-left: 20px;
	margin-bottom: 5px;
}
.membpress .membpress_welcome_login_group input[type=radio]:first-child {
	margin-left: 0;
}
/*
 Collapse expand all meta boxes in membpress
*/
.membpress .membpress_settings_collapse_expand {
	line-height: 29px;
	margin-top: 20px;
	font-size: 0.95em;
}
.membpress .membpress_settings_collapse_expand .dashicons {
	margin-top: 5px;
}
/*
Updated message bar close button on right
*/
.membpress .membpress_updated_close {
	float: right;
}
.membpress .updated, .membpress .error p{
	color: #272727 !important;
}

/*
CSS responsive fix for the expand/collapse buttons
*/
@media all and (max-width: 780px) {
.membpress_settings_collapse_expand, .membpress_settings_heading {
	float: none !important;
}
.membpress_settings_collapse_expand {
	margin-top: 0 !important;
	margin-bottom: 5px;
}
.membpress_settings_heading {
	margin-bottom: 0 !important;
}
}
/**
Miscellaneous
*/
.membpress_pull-right {
	float: right;
}
.membpress_pull-left {
	float: left;
}
.membpress_clear {
	clear: both;
}
.membpress_hidden {
	display: none;
}
.membpress-admin-menu-sep {
	height: 1px;
	background-color: #7C7C7C;
	display: block;
}
.membpress_subs_recurring_txt, .membpress_subs_onetime_txt, .membpress_subs_recurring_for, .membpress_subs_onetime_for, .membpress_subs_lifetime_txt, .membpress_subs_lifetime_for, .membpress_subs_duration {
	display: none;
}
/*
Fix the width of the post/page ID column in edit post screen
*/
.fixed .column-post_ID, .fixed .column-page_ID {
	width: 5%;
}
.fixed .column-category_ID, .fixed .column-tag_ID {
	width: 7%;
}
/*
Create rules for textbox/textarea sizes
*/
.membpress_span1 {
	width: 60px;
}
.membpress_span2 {
	width: 120px;
}
.membress_span-full {
	width: 100%;
}
/*
Clear background clear of the read only textboxes and textareas
*/
.membpress input.readonly, .membpress input[readonly], .membpress textarea.readonly, .membpress textarea[readonly] {
	background-color: #FFF;
}

/*
Admin menu bar customizations for membpress links
*/
#wpadminbar #wp-admin-bar-membpress_admin_bar > .ab-item {
	background-image:url('<?php echo plugins_url(); ?>/membpress/resources/images/icon.png') !important;
    background-repeat:no-repeat;
    background-position:8px;
    padding-left:34px;
}