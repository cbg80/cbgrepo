<?php
function printOutReplyBox() {
	$htmlFormat = <<<EOT
<div>
<form enctype='multipart/form-data' action='../webapp_controllers/front_controller.php' method='POST'>
<input type='hidden' name='action' value='doMakePost'/>
Image title:&nbsp<input type='text' name='imgTitle' placeholder='short picture's description'/><br/>
<input type='hidden' name='MAX_FILE_SIZE' value='2097152'/>
Picture:&nbsp;<input type='file' name='imgFile' required/>%s<br/>
<input type='submit' value='Post'/>
</form>
</div>
EOT;
	if (isset ( $_REQUEST ['error'] ['code'], $_REQUEST ['error'] ['message'] ) and in_array ( $_REQUEST ['error'] ['code'], [ 
			1,
			2,
			3,
			4,
			6,
			7,
			8 
	    IMG_THREAD_UPLOAD_ERR_CORRUPTED['code'],
	    IMG_THREAD_UPLOAD_ERR_UNKNOWN['code'],
	    IMG_THREAD_UPLOAD_ERR_WIDER['code'],
	    IMG_THREAD_UPLOAD_ERR_HIGHER['code'],
	    IMG_THREAD_UPLOAD_ERR_HEAVIER['code'],
	    IMG_THREAD_UPLOAD_ERR_FORBIDDEN['code'],
	    IMG_THREAD_UPLOAD_ERR_MOVED['code'],
	] )) {
		$htmlErrorMessageFormat = <<<EOT
<span style="color:red">%s</span>
EOT;
		$htmlErrorMessage = sprintf ( $htmlErrorMessageFormat, $_REQUEST ['error'] ['message'] );
	} else {
		$htmlErrorMessage = '';
	}
	printf ( $htmlFormat, $htmlErrorMessage );
}
?>