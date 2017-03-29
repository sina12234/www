/**
* @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
* For licensing, see LICENSE.md or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config ) {
	config.language = 'zh-cn';
	config.image_previewText = "  ";//CKEDITOR.tools.repeat( '___ ', 100 );
	config.extraPlugins= 'imageresize';
	config.filebrowserWindowWidth= '800';
	config.filebrowserWindowHeight= '5000';
	config.filebrowserFlashUploadUrl = '/file.main.upload?type=flash';
	config.filebrowserFlashBrowseUrl = '/file.main.browse?type=flash';
	config.filebrowserImageBrowseUrl= '/file.main.browse?type=image';
	config.filebrowserImageUploadUrl= "/file.main.upload?type=image",
	config.toolbar= [ ['Source','-','Templates'], 
	['Bold','Italic','Underline','RemoveFormat','-','Subscript','Superscript'], 
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'], 
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'], 
	['Link','Unlink'], 
	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar', 'PageBreak'], 
	//'/', 
	['Styles','Format','Font','FontSize'], 
	['TextColor','BGColor'], 
	['Maximize','ShowBlocks'] 
	]; 

	// config.uiColor = '#AADC6E';
	/*
	config.toolbar= 
	[ 
	['Source','-','Save','NewPage','Preview','-','Templates'], 
	['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print','SpellChecker','Scayt'], 
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'], 
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button', 'ImageButton','HiddenField'], 
	'/', 
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'], 
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'], 
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'], 
	['Link','Unlink','Anchor'], 
	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar', 'PageBreak'], 
	'/', 
	['Styles','Format','Font','FontSize'], 
	['TextColor','BGColor'], 
	['Maximize','ShowBlocks','-','About'] 
	]; 
	*/
};
