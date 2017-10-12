<div class="wrap">
<h1>Настройка плагина</h1>
<form method="post" action="options.php"> 
<?php settings_fields( 'myoption-group' ); 
$args1 = array('name' => 'Submission_successful_page','selected' => get_option('Submission_successful_page'),  'class' =>'success_page_selector','show_option_none' => 'Отсутствует');
$args2 = array('name' => 'Submission_failed_page','selected' => get_option('Submission_failed_page'), 'class' =>'fail_page_selector','show_option_none' => 'Отсутствует');?>
 <table class="form-table">
        <tr valign="top">
        <th scope="row">Страница для перенаправления пользователей после <i>успешного</i> завершения теста</th>
        <td><?php wp_dropdown_pages($args1);?></td>
        </tr>
        <tr valign="top">
        <th scope="row">Страница для перенаправления пользователей после <i>неуспешного</i> завершения теста</th>
        <td><?php wp_dropdown_pages($args2);?></td>
        </tr>
    </table>
    

<?php submit_button();?>
</form>
</div>