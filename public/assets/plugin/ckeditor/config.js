/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.allowedContent = true;
	config.extraPlugins = 'lineheight';
	config.line_height="1;1.1;1.2;1.3;1.4;1.5;1.6;1.7;1.8;1.9;2.0";
	config.entities_latin = false;

	config.extraPlugins = 'btgrid';
	config.contentsCss = 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css';
	config.mj_variables_bootstrap_css_path = 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css';
	config.allowedContent = true;
	config.bootstrapGrid_container_extra_large = 1140;
	config.bootstrapGrid_container_large = 960;
	config.bootstrapGrid_container_medium = 720;
	config.bootstrapGrid_container_small = 540;
	config.bootstrapGrid_grid_columns = 12;
};
