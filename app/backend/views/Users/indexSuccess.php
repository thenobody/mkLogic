<table border="1">
	<?php foreach( $users as $user ): ?>
	<tr>
		<td><?php echo $user->Id ?></td>
		<td><?php echo $user->Username ?></td>
		<td><?php echo $user->Password ?></td>
		<td><?php echo $user->EMail ?></td>
		<td><?php echo $user->Active ?></td>
	</tr>
	<?php endforeach; ?>
</table>