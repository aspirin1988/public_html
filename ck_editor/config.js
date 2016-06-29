/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.addStylesSet( 'mystyle',
[
	{ name : 'H1', element : 'h1' },
	{ name : 'H2', element : 'h2' },
	{ name : 'H3', element : 'h3' },
	{ name : 'Image', element : 'img', attributes : { 'class' : 'image' } },
	{ name : 'links', element : 'ul', attributes : { 'class' : 'links' } },
	{ name : 'empty', element : 'ul', attributes : { 'class' : ' ' } },
	{ name : 'list1', element : 'ul', attributes : { 'class' : 'list1' } },
	{ name : 'list2', element : 'ol', attributes : { 'class' : 'list2' } },
	{ name : 'red_links', element : 'li', attributes : { 'class' : 'red' } },
	{ name : 'empty', element : 'li', attributes : { 'class' : ' ' } },
	{ name : 'txt1', element : 'span', attributes : { 'class' : 'txt1' } },
	{ name : 'txt2', element : 'span', attributes : { 'class' : 'txt2' } },
	{ name : 'txt3', element : 'span', attributes : { 'class' : 'txt3' } },
	{ name : 'txt4', element : 'span', attributes : { 'class' : 'txt4' } },
	{ name : 'grid_6', element : 'div', attributes : { 'class' : 'grid_6' } },
	{ name : 'grid_3', element : 'div', attributes : { 'class' : 'grid_3' } },
	{ name : 'imgcaption', element : 'a', attributes : { 'class' : 'imgcaption' } }
]);
				
CKEDITOR.editorConfig = function( config )
{
	
	// Define changes to default configuration here. For example:
	 config.language = 'ru';
     config.filebrowserImageBrowseUrl = '/ck_editor/filemanager/browser/default/browser.html?Type=Image&Connector=/ck_editor/filemanager/connectors/php/connector.php';
     config.filebrowserFileBrowseUrl  = '/ck_editor/filemanager/browser/default/browser.html?Type=File&Connector=/ck_editor/filemanager/connectors/php/connector.php';
     config.filebrowserFlashBrowseUrl = '/ck_editor/filemanager/browser/default/browser.html?Type=Flash&Connector=/ck_editor/filemanager/connectors/php/connector.php';
     config.filebrowserBrowseUrl      = '/ck_editor/filemanager/browser/default/browser.html?Type=File&Connector=/ck_editor/filemanager/connectors/php/connector.php';
	 
	 config.stylesSet = 'mystyle';
	 config.contentsCss='/css/work.css';
	 
	// config.uiColor = '#AADC6E';
};


