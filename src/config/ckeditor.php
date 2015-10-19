<?php

return array(
    'config' => [
		'language' => 'en',
		'filebrowserBrowseUrl' => null, // ckfinder/ckfinder.html
		'filebrowserImageBrowseUrl' => null, //ckfinder/ckfinder.html?type=Images
		'filebrowserFlashBrowseUrl' => null, //ckfinder/ckfinder.html?type=Flash
		'filebrowserUploadUrl' => null, //ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files
		'filebrowserImageUploadUrl' => null, //ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images
		'filebrowserFlashUploadUrl' => null, //ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash
		'allowedContent' => true,
	],
	
	'type' => \Simexis\CKEditor\CKEditor::TYPE_FULL,
	
	'toolBarConfig' => null,

	'replaceByClass' => '.event-ckeditor',

    'height' => 300
);