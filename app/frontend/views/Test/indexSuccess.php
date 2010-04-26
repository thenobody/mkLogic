<table border="1">
	<tr>
		<th></th>
		<th>description</th>
		<th>result</th>
	</tr>
	<tr>
		<td>1.</td>
		<td>add new user (invalid username) - should fail</td>
		<td><?php echo ($test1) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>2.</td>
		<td>add new user (valid username) - should succeed</td>
		<td><?php echo ($test2) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>3.</td>
		<td>log in with created username</td>
		<td><?php echo ($test3) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>4.</td>
		<td>create new list (named test1)</td>
		<td><?php echo ($test4) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>5.</td>
		<td>create new item in list test1 (value testitem1; color #000000)</td>
		<td><?php echo ($test5) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>6.</td>
		<td>edit item in list test1 (value testitem1 =&gt; testitem2; color #000000 = &gt; #ffffff)</td>
		<td><?php echo ($test6) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>7.</td>
		<td>remove item in list test1 (value testitem2; color #ffffff)</td>
		<td><?php echo ($test7) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>8.</td>
		<td>add tag 'testtag1' for list 'test1'</td>
		<td><?php echo ($test8) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>9.</td>
		<td>remove tag 'testtag1' for list 'test1'</td>
		<td><?php echo ($test9) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>10.</td>
		<td>remove list (named test1)</td>
		<td><?php echo ($test10) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>11.</td>
		<td>log out with created username</td>
		<td><?php echo ($test11) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>12.</td>
		<td>remove created user (nonexistent username) - should fail</td>
		<td><?php echo ($test12) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
	<tr>
		<td>13.</td>
		<td>remove created user (existent username) - should succeed</td>
		<td><?php echo ($test13) ? "<span style='color: green;'>passed</span>" : "<span style='color: red;'>failed</span>" ?></td>
	</tr>
</table>

<?php if($test_all): ?>
	<h1 style="color: green;">All tests passed OK!</h1>
<?php else: ?>
	<h1 style="color: red;">Some tests have failed!</h1>
<?php endif; ?>