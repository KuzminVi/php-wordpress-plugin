
<h3> Созданные тесты </h3>

<script>
 var p = "<?php echo  admin_url('admin-ajax.php'); ?>";
 var x = "<?php echo  admin_url('admin-post.php'); ?>";
function deleteTest()
{
	var test = jQuery("#TestToDelete").val();
	jQuery.ajax({
		url: p,
		type: "post",
		data: {action: "deleteTestDB",
				testname:test
				}
	});
	jQuery("#"+test).remove();
}
</script>
<?php
global $wpdb;
$tests = $wpdb->get_col('SELECT name FROM kv_tests');
if (count($tests) > 0)
{
echo '<ol>';
for ($i=0; $i < count($tests); $i++)
{
	echo '<li id='.$tests[$i].'>';
	echo  $tests[$i] . '</li>';
}
echo '</ol>';
echo '<p> Какой тест вы хотите удалить? </p>';
echo '<p> Внизу введите его наименование </p>';
echo '<input type="text" id="TestToDelete"/>';
echo '<br/><br/>';
echo '<button  onclick="deleteTest()"> Удалить </button>';
}
else
{
	echo '<p>  Список созданных тестов пуст </p>';
}
?>
