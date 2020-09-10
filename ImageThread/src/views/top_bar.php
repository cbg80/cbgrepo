<?php
printOutTopBar ();
function printOutTopBar() {
	$htmlFormat = <<<EOT
<div style='overflow:hidden'>
<div style='float:left'>
Posts:&nbsp;%d
</div>
<div style='float:left'>
%s
<form action='../webapp_controllers/front_controller.php' method='POST'>
<input type='hidden' name='action' value='doExportPosts'/>
<input type='submit' value='Export'/>
</form>
</div>
<div style='float:left'>
Views:&nbsp;%d
</div>
</div>
EOT;
	if (isset ( $_REQUEST ['nOfPosts'] )) {
		$nOfPosts = ( int ) $_REQUEST ['nOfPosts'];
	} else {
		$nOfPosts = 0;
	}
	if (isset ( $_REQUEST ['nOfViews'] )) {
		$nOfViews = ( int ) $_REQUEST ['nOfViews'];
	} else {
		$nOfViews = 0;
	}
	if (isset ( $_REQUEST ['error'] ['code'], $_REQUEST ['error'] ['message'] ) and $_REQUEST ['error'] ['code'] == - 8) {
		$htmlErrorMessageFormat = <<<EOT
<p style="color:red">%s</p>
EOT;
		$htmlErrorMessage = sprintf ( $htmlErrorMessageFormat, $_REQUEST ['error'] ['message'] );
	} else {
		$htmlErrorMessage = '';
	}
	printf ( $htmlFormat, $nOfPosts, $htmlErrorMessage, $nOfViews );
}
?>