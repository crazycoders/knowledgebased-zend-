/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{

         CKEDITOR.config.resize_enabled = true;
         CKEDITOR.config.width = 300;
         CKEDITOR.config.autoGrow_minHeight = 100;
         CKEDITOR.config.autoGrow_maxHeight = 150;
         CKEDITOR.config.toolbar_Full =
[
    //['Source','-','Save','NewPage','Preview','-','Templates'],
  //  ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
  //  ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
//  ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'], // here i disable the form tools
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
   // ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
   // ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
   // ['BidiLtr', 'BidiRtl' ],
    ['Link','Unlink','Anchor'],
   // ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
    '/',
   // ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
  //  ['Maximize', 'ShowBlocks','-','About']
];

};
