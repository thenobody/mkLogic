<?php
	if( $mk_request->hasErrors() )
		foreach( $mk_request->getErrors()->getParameterNames() as $name ): ?>
	<p><?php echo $mk_request->getError( $name ) ?></p>
		<?php endforeach; ?>

<form method="post" action="" id="login" name="login">
	<input type="text" name="token" value="" />
	<button name="submit" type="submit" value="">login</button>
</form>