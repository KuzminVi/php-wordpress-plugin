<script>
	function showStep(elemid)
	{
	var tabcontent = document.getElementsByClassName("tabcontent");
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
		}
	jQuery("#"+elemid)[0].style.display = "block";
	}
</script>
<script>
  jQuery(document).ready(function() {
    showStep('stepOne');
});
</script>
<p> Конструктор теста </p>
<hr/>
<div class="tab">
<button id="defaultTab" class="tablinks" onclick="showStep('stepOne')"> Название теста </button>
<button class="tablinks" onclick="showStep('stepTwo')"> Вопросы для теста</button>
<button class="tablinks" onclick="showStep('stepThree')"> Результаты для теста</button>
</div>
<ul id="errorMessages">

</ul>
<div id="stepOne" class="tabcontent">
<p> Введите название теста </p>
<input type="text" id="testname" />
</div>

<div id="stepTwo" class="tabcontent">
<p>Введите вопрос </p>
<input id="label" type="text"/>
<p> Тип вопроса </p>
<input type="radio" name="questiontype" id="radioquestion" onchange="radioquestionSlide()">С единичным выбором </input>
<input type="radio" name="questiontype" id="checkboxquestion" onchange="radioquestionSlide()">С множественным выбором </input>
<input type="radio" name="questiontype" id="yesnoquestion" onchange="radioquestionSlide()">Да/Нет вопрос </input>
<input type="radio" name="questiontype" id="scalequestion" onchange="radioquestionSlide()">Вопрос со шкалой </input>

 <div id="AnswerCategoriesSlideContent" hidden>
 <p> Введите категории на которые влияет данный ответ / вопрос </p>
 <table id="AnswerCategories">
 <thead> <th>Категория</th> <th>Вес в условных единицах</th></thead>
 <tr><td> <input type="text" id="AnswerCategoryText"> </input> </td><td><input type="text" class = "number-field" id="AnswerPointsText"> </input></td></tr>
 </table>
 <button onclick = "insertCategory()"> Вставить категорию </button>
 <button onclick = "deleteCategory()"> Удалить категорию </button><br/>
 </div>
 <hr/>
<div id="scaleSlidingContent" hidden>
Минимальное значение <input type = "text" id="min"/>
Максимальное значение <input type = "text" id="max"/>
<hr/>
</div>
<div id="yesnoSlidingContent" hidden>
<p>Начисление баллов за ответ : </p>
	<label><input type="radio" id="yespoint" name="point"/> "Да" </label>
	<label><input type="radio" id="nopoint" name="point"/> "Нет" </label>
	<hr/>
</div>
<div id="SlidingContent" hidden>
 <p> Введите ответ </p>
 <input type="text" id="AnswerText"></input>
 
 
 <p>Ответы</p>
 <div id="AnswerList">
 
 </div>
 <br/>
 <button onclick="createAnswer()">Вставить ответ </button>
 <button onclick="deleteAnswer()">Удалить ответ </button>
 <hr/>
 </div>

<div>
	<script type="text/javascript">
   var p = "<?php echo  admin_url('admin-ajax.php'); ?>";
   var x = "<?php echo  admin_url('admin-post.php'); ?>";
	</script>
	<button onclick="createQuestion(p)"> Создать вопрос </button>
	<button onclick="deleteQuestion(p)" style="position: absolute; right: 100px;"> Удалить последний вопрос </button>
</div>
<div id=newformcode>
<form id="newform">
</form>
</div>
</div>

<hr/>

<div id="stepThree" class="tabcontent">
<div>
<div style="display:inline-block">
<p> Название результата</p>
<input type="text" id="resultname"/> 
</div>
<div style="display:inline-block">
<p>Описание результата</p>
<input type="textarea" id="resultdesc"/> 
</div>
<div style="display:inline-block">
<p></p>
<label style="margin:20px"><input type="checkbox" value="Default" id="resultdefault" onclick="defaultResultSet()"/> По умолчанию</label>
</div>
</div>
<div id="resformula">
<hr/>
<p>Формула</p>
<table id="resultcategories">
<thead> <th> Категория </th> <th>&gt &lt = </th> <th> Баллы </th> </thead>
<tr> <td> <select id = "res_name_1" class="res_name" style="width:100%;"/> </select></td> 
	<td><select class = "res_compare">
		<option value=">">&gt </option>
		<option value=">=">&gt=</option>
		<option value="<">&lt </option>
		<option value="<=">&lt=</option>
		<option value="==">= </option>
		</select> </td> 
	<td><input type="text" class = "number-field" name="catpoints"/> </td>
	<td><select class = "res_logic">
		<option value="and"> и </option>
		<option value="||"> или </option>
		</select> </td> 
</tr>
</table>
<button onclick="insertResultCategory()"> Добавить категорию </button>
<button onclick="deleteResultCategory()"> Удалить категорию </button>
</div>
<hr/>
<button onclick="createResult(p)"> Добавить результат </button>
<button onclick="deleteResult(p)" style="position: absolute; right: 100px;"> Удалить последний результат </button>
<hr/>
<div>
<p> Текущие результаты </p>
<ol id="curresults">
</ol>
<hr/>
</div>
</div>

<button id="finishbutton" disabled ="" onclick="finish(p,x)"> Закончить </button>
</div>


