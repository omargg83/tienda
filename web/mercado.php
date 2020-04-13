<?php

	$id=$_REQUEST['id'];
	echo $id;




	if (isset($_GET["id"], $_GET["topic"])) {
	    http_response_code(200);
	    return;
	}


?>
