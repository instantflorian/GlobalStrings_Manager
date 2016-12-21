/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * backend_body.js
 * This file provides needed javascript for use with addonMonitor
 * 
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */


var MODULE_URL = MODULE_URL ? MODULE_URL : WB_URL + "/modules/global_strings/"; 
	
$(document).ready(function() {	
	if($(".clipper").length){
		$.insert( MODULE_URL + "/js/clipboard.min.js");
		var clipboard = new Clipboard('.clipper');
	}
	if($(".hilite").length){
		$(".hilite").removeClass("hilite", 1000).addClass("hilite", 1000).removeClass("hilite", 1500);
	}
	
	// confirmation
	$(".trash").click(function (e) {
		var result = window.confirm('In den Trash verschieben?');
		if (result == false) {
			e.preventDefault();
		};
	});
	
	$(".delete").click(function (e) {
		var result = window.confirm('Sind Sie sicher? \nWenn Sie jetzt löschen, kann der Inhalt nicht wiederhergestellt werden.');
		if (result == false) {
			e.preventDefault();
		};
	});
	
});	
