<?php
	@session_start();
	@session_destroy();
	header ("Location: ".$HTTP_REFERER);
?>
                