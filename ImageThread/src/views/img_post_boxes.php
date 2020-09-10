<?php
printOutImgPostBoxes ();
function printOutImgPostBoxes() {
	if (isset ( $_REQUEST ['posts'] ) and is_array ( $_REQUEST ['posts'] ) and count ( $_REQUEST ['posts'] ) > 0) {
		$htmlFormat = <<<EOT
<div>
%s
</div>
EOT;
		$htmlPostFormat = <<<EOT
<div>
<p>%s</p>
<img src="%s%s" alt="%s"/>
</div>
EOT;
		$htmlPosts = array ();
		foreach ( $_REQUEST ['posts'] as $post ) {
		    $htmlPosts [] = sprintf ( $htmlPostFormat, $post->imageTitle, '/' . REL_PATH_TO_POST_IMG . '/', $post->imageFileName, $post->imageTitle );
		}
		$strHtmlPosts = implode ( PHP_EOL, $htmlPosts );
		printf ( $htmlFormat, $strHtmlPosts );
	}
}
?>