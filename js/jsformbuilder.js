
var questionnum = 1;
var ansnum = 0;
var resultnum = 0;

function radioquestionSlide()
{
if (jQuery("#radioquestion")[0].checked == true || jQuery("#checkboxquestion")[0].checked == true)
{
	jQuery("#SlidingContent").slideDown();
}
else
{
	jQuery("#SlidingContent").slideUp();
}
if (jQuery("#scalequestion")[0].checked == true)
{
	jQuery("#scaleSlidingContent").slideDown();
	jQuery("#AnswerPointsText")[0].hidden = true;
	jQuery("#AnswerCategories>thead th")[1].hidden = true;
}
else
{
	jQuery("#scaleSlidingContent").slideUp();
	jQuery("#AnswerPointsText")[0].hidden = false;
	jQuery("#AnswerCategories>thead th")[1].hidden = false;
}
if (jQuery("#yesnoquestion")[0].checked == true)
{
	jQuery("#yesnoSlidingContent").slideDown();
}
else
{
	jQuery("#yesnoSlidingContent").slideUp();
}
jQuery("#AnswerCategoriesSlideContent").slideDown();
}

function insertCategory()
{
	var table=jQuery("#AnswerCategories")[0];
	
	var a = document.createElement("tr");
	
	var b = document.createElement("td");
	var c = document.createElement("input");
	c.type = "text";
	
	b.appendChild(c);
	a.appendChild(b);
	
	var d = document.createElement("td");
	var e = document.createElement("input");
	e.type = "text";
	e.className = "number-field";
	if (jQuery("#scalequestion")[0].checked == true){d.hidden =true}
	d.appendChild(e);
	a.appendChild(d);
	
	
	table.appendChild(a);
}
function insertResultCategory()
{
	var table=jQuery("#resultcategories")[0];
	var a = document.createElement("tr");
	
	var b = document.createElement("td");
	var c = document.createElement("select");
	c.className="res_name";
	c.innerHTML=jQuery("#res_name_1")[0].innerHTML;
	c.style.width="100%";
	
	b.appendChild(c);
	a.appendChild(b);
	
	var b = document.createElement("td");
	var c = document.createElement("select"); 
	c.className = "res_compare";
	var c1 = document.createElement("option");c1.value=">";c1.appendChild(document.createTextNode(">"));
	var c11 = document.createElement("option");c11.value=">=";c11.appendChild(document.createTextNode(">="));
	var c2 = document.createElement("option");c2.value="<";c2.appendChild(document.createTextNode("<"));
	var c21 = document.createElement("option");c21.value="<=";c21.appendChild(document.createTextNode("<="));
	var c3 = document.createElement("option");c3.value="==";c3.appendChild(document.createTextNode("="));
	
	c.appendChild(c1);c.appendChild(c11);c.appendChild(c2);c.appendChild(c21);c.appendChild(c3);
	b.appendChild(c);
	a.appendChild(b);
	
	var d = document.createElement("td");
	var e = document.createElement("input");
	e.type = "text";
	e.className = "number-field";
	
	d.appendChild(e);
	a.appendChild(d);
	
	var f = document.createElement("td");
	var g = document.createElement("select");
	g.className = "res_logic";
	var g1 = document.createElement("option");g1.value="and";g1.appendChild(document.createTextNode("и"));
	var g2 = document.createElement("option");g2.value="or";g2.appendChild(document.createTextNode("или"));
	
	g.appendChild(g1);g.appendChild(g2);
	f.appendChild(g);
	a.appendChild(f);
	table.appendChild(a);
}
function deleteResultCategory()
{
	jQuery("#resultcategories").children("tr").last().remove();
}
function deleteCategory()
{
	jQuery("#AnswerCategories").children("tr").last().remove();
}
function ResultFilled()
{
	if (jQuery("#resultdefault")[0].checked == false){
	for(var i =0; i < jQuery("#resultcategories input").length; i++)
	{
		if (jQuery("#resultcategories input")[i].value.trim() == "") { return false;}
		if (jQuery("#resultcategories input")[i].className == "number-field" && isNaN(jQuery("#resultcategories input")[i].value)) { return false;}
	}
	}
	return true;
}
function createResult(path)
{
	var errors = [];
	jQuery("#errorMessages").find(".Error").remove();
	if (jQuery.trim( jQuery("#testname")[0].value ) == "" ) // testname  empty
	{
		errors.push("Название теста отсутствует");
	}
	if (jQuery.trim(  jQuery("#resultname")[0].value ) == "") //resultname  empty
	{
		errors.push("Название результата отсутствует");
	}
	if (jQuery.trim(  jQuery("#resultdesc")[0].value ) == "") //resultdesc  empty
	{
		errors.push("Описание результата отсутствует");
	}
	if ( !ResultFilled())
	{
		errors.push("Не все категории результата заполнены");
	}
	
	if (errors.length > 0)
	{
	 for (var i = 0; i < errors.length; i++)
	 {
		var a = document.createElement("li"); a.className = "Error";a.appendChild(document.createTextNode(errors[i]));
		jQuery("#errorMessages")[0].appendChild(a);
	 }
	}
	else
	{
	jQuery("#errorMessages").find(".Error").remove();
	resultnum += 1;
	updatedbResult(path);
	var n = document.createElement("li");
	if (jQuery("#resultdefault")[0].checked == true)
	{
	n.className = "default-res";
	var a = document.createElement("b");
	a.appendChild(document.createTextNode("&nbsp&nbsp&nbsp&nbsp По умолчанию"));
	n.appendChild(document.createTextNode(jQuery("#resultname")[0].value)) ;
	n.appendChild(a);
	jQuery("#resultdefault").click();
	jQuery("#resultdefault")[0].disabled = true;
	}
	else{n.appendChild(document.createTextNode(jQuery("#resultname")[0].value)) ;}
	jQuery("#curresults")[0].appendChild(n);
	if (resultnum > 0)
	{
	jQuery("#testname")[0].disabled=true;
	}
	else
	{
	jQuery("#testname")[0].disabled=false;
	}
	}
}
function defaultResultSet()
{
	if (jQuery("#resultdefault")[0].checked == true)
	{
	jQuery("#resformula").hide();
	}
	else
	{
	jQuery("#resformula").show();
	}
}
function updatedbResult(path)
{
	var ResultCatFormula ="";
	if (jQuery("#resultdefault")[0].checked == true)
	{
		ResultCatFormula = "Default";
	}
	else
	{
	for (var j = 0; j < jQuery("#resultcategories tr").length - 1; j=j+1)
		{
			ResultCatFormula = ResultCatFormula + jQuery("#resultcategories .res_name:eq("+j+")").val() + jQuery("#resultcategories .res_compare:eq("+j+")").val()
								+ jQuery("#resultcategories input")[j].value;
			if ( j < (jQuery("#resultcategories tr").length - 2) ) {ResultCatFormula = ResultCatFormula + " "+jQuery("#resultcategories .res_logic:eq("+j+")").val()+" ";} // and goes after || in php
		}
	}
	 jQuery.ajax({
            url: path,
            type: "post",
            data: {	action: "createTestResultDB", // for ajax hook; refer to main php file
					testname: jQuery("#testname")[0].value,
					resultname : jQuery("#resultname")[0].value,
					resultdesc : jQuery("#resultdesc")[0].value,
					resultformula : ResultCatFormula},
			success: function(data){ 
					if (data!=""){alert(data);deleteResult()};
					}
        });
}
function updatedb(path)
{
	var answersList = [];
	for (var i = 0; i < ansnum ; i++)
	{
		var a = {};
		a.answer = jQuery("#num" + i + ">input")[0].value;
		a.categories = [];
		for (var j = 0; j < jQuery("#num" + i + " tr").length; j++)
		{
			var category = {};
			category.name = jQuery("#num" + i + " .cat"+j+" input")[0].value;
			category.weigth = jQuery("#num" + i + " .cat"+j+" input")[1].value;
			a.categories.push(category);
		}
		answersList.push(a);
	}
	
	 jQuery.ajax({
            url: path,
            type: "post",
            data: {	action: "createTestDB", // for ajax hook; refer to main php file
					testname: jQuery("#testname")[0].value,
					question : jQuery("#label")[0].value,
					answers : answersList},
			success: function(data){ 
				console.log(data);
				if (data==="Уже есть такой тест")
				{
				alert(data);
				deleteQuestion();
				}
				else
				{
					var categories = JSON.parse(data);
					var sel = jQuery("#res_name_1")[0];
					sel.innerHTML = "";
					for(var i = 0; i < categories.length; i++) 
					{
					var opt = document.createElement('option');
					opt.innerHTML = categories[i];
					opt.value = categories[i];
					sel.appendChild(opt);
					}
					jQuery(".res_name").each(function(){jQuery(this)[0].innerHTML = jQuery("#res_name_1")[0].innerHTML;});
				}
				}
        });
}
function IsQuestionAnswerFilled()
{
	if ( jQuery("#radioquestion")[0].checked == true || jQuery("#checkboxquestion")[0].checked == true)
	{
	if (jQuery("#AnswerList>div").length < 2)
	{
		return false;
	}
	for(var i =0; i < jQuery("#AnswerList input").length; i++)
	{
		if (jQuery("#AnswerList input")[i].value.trim() == "") { return false;}
		if (jQuery("#AnswerList input")[i].className == "number-field" && isNaN(jQuery("#AnswerList input")[i].value)) { return false;}
	}
	return true;
	}
	if (jQuery("#yesnoquestion")[0].checked == true )
	{
		for(var i =0; i < jQuery("#AnswerCategories input").length; i++)
		{
		if (jQuery("#AnswerCategories input")[i].value.trim() == "") { return false;}
		if (jQuery("#AnswerCategories input")[i].className == "number-field" && isNaN(jQuery("#AnswerCategories input")[i].value)) { return false;}
		}
		if (!jQuery("#yespoint").is(':checked') && !jQuery("#nopoint").is(':checked'))
		{
		return false;
		}
		return true;
	}
	if (jQuery("#scalequestion")[0].checked == true )
	{
		var min = jQuery("#min")[0].value;
		var max = jQuery("#max")[0].value;
		for(var i =0; i < jQuery("#AnswerCategories input").length; i++)
		{
		if (jQuery("#AnswerCategories input")[i].value.trim() == "" && jQuery("#AnswerCategories input")[i].hidden == "") { 
			return false;
			}
		}
		if (isNaN(min) || isNaN(max)) {return false;}
		if (min > max || min == max) {return false;}
		return true;
	}
}
function createQuestion(path)
{

	var errors = [];
	jQuery("#errorMessages").find(".Error").remove();
	if (jQuery.trim( jQuery("#testname")[0].value ) == "" ) // testname  empty
	{
		errors.push("Название теста отсутствует");
	}
	if (jQuery.trim(  jQuery("#label")[0].value ) == "") //question  empty
	{
		errors.push("Название вопроса отсутствует");
	}
	if ( !IsQuestionAnswerFilled())
	{
		if (jQuery("#scalequestion")[0].checked == true)
		{
		errors.push("Не все категории заполнены или минимальное значение НЕ ниже максимального");
		}
		else if(jQuery("#yesnoquestion")[0].checked == true)
		{
		errors.push("Не все категории заполнены");
		}
		else
		{
		errors.push("Не все категории заполнены или в вопросе менее 2-х возможных ответов");
		}
	}
	if (errors.length > 0)
	{
	 for (var i = 0; i < errors.length; i++)
	 {
		var a = document.createElement("li"); a.className = "Error";a.appendChild(document.createTextNode(errors[i]));
		jQuery("#errorMessages")[0].appendChild(a);
	 }
	}
	else
	{
	if (jQuery("#yesnoquestion")[0].checked == true)
	{
		createYesNo();
	}
	if (jQuery("#scalequestion")[0].checked == true)
	{
		createScale();
	}
	create();
	updatedb(path);
	ClearAllAnswers();
	jQuery("#label")[0].value = "";
	questionnum+=1;
	if (questionnum >= 2)
	{
	jQuery("#testname")[0].disabled=true;
	jQuery("#finishbutton")[0].disabled=false;
	}
	else
	{
	jQuery("#testname")[0].disabled=false;
	jQuery("#finishbutton")[0].disabled=true;
	}
	}

}
function deleteQuestion(path)
{
	if (questionnum > 1)
	{
	jQuery.ajax({
            url: path,
            type: "post",
            data: {	action: "deleteTestQuestionDB", // for ajax hook; refer to main php file
					testname: jQuery("#testname")[0].value
					}, 
			success: function(data)
			{
				console.log(data);
				if (questionnum > 1)
				{
				questionnum = questionnum - 1;
				}
				if (questionnum >= 2)
	{
	jQuery("#testname")[0].disabled=true;
	jQuery("#finishbutton")[0].disabled=false;
	}
	else
	{
	jQuery("#testname")[0].disabled=false;
	jQuery("#finishbutton")[0].disabled=true;
	}
			var categories = JSON.parse(data);
					var sel = jQuery(".catname")[0];
					sel.innerHTML = "";
					for(var i = 0; i < categories.length; i++) 
					{
					var opt = document.createElement('option');
					opt.innerHTML = categories[i];
					opt.value = categories[i];
					sel.appendChild(opt);
					}
			jQuery(".res_name").each(function(){jQuery(this)[0].innerHTML = jQuery("#res_name_1")[0].innerHTML;});
			}
        });
	jQuery("#newform").children(".kv_test_question").last().remove();
	}
	
}
function deleteResult(path)
{
	if (resultnum > 0)
	{
	jQuery.ajax({
            url: path,
            type: "post",
            data: {	action: "deleteTestResultDB", // for ajax hook; refer to main php file
					testname: jQuery("#testname")[0].value
					},
			success: function(data)
			{
				resultnum = resultnum - 1;
				if (jQuery("#curresults").children().last()[0].className == "default-res") {  jQuery("#resultdefault")[0].disabled = false;}
				jQuery("#curresults").children().last().remove();
				if (resultnum == 0)
				{
					jQuery("#testname")[0].disabled=false;
				}
			}
        });
	
	}
		
}
function create()
{
var x = jQuery("#label")[0].value; 
	var question = document.createElement("div"); question.className ="kv_test_question";
	var label = document.createElement("p"); label.className = "kv_test_question_label";
	var text = document.createTextNode(questionnum+". "+x);
	label.appendChild(text);
	question.appendChild(label);
	
	if (jQuery("#checkboxquestion")[0].checked == true)
	{
	var x = jQuery("#AnswerList > div").children("input").length;
	for (i = 0; i < x; i++)
	{
	var f = document.createElement("input");
	f.type="checkbox";
	f.name=""+questionnum+"[]";
	f.value= (jQuery("#AnswerList > div").children("input"))[i].value;
	var l = document.createElement("label");
	l.appendChild(f);
	l.appendChild(document.createTextNode(f.value));
	question.appendChild(l);
	
	}
	jQuery("#newform")[0].appendChild(question);
	}
	else
	{
	var n = jQuery("#AnswerList")[0];
	var x = jQuery("#AnswerList > div").children("input").length;
	for (i = 0; i < x; i++)
	{
	var f = document.createElement("input");
	f.type="radio";
	f.name="" + questionnum;
	f.required="required";
	f.value= (jQuery("#AnswerList > div").children("input"))[i].value;
	var l = document.createElement("label");
	l.appendChild(f);
	l.appendChild(document.createTextNode(f.value));
	question.appendChild(l);
	}
	jQuery("#newform")[0].appendChild(question);
	}
}
function createAnswer()
{

var answer = document.createElement("div");
answer.id = "num" + ansnum;
var x = document.createElement("input");
x.type= "text";
x.value=jQuery("#AnswerText")[0].value;
answer.appendChild(x);
var catnum = 0;

var table = document.createElement("table");
var b = document.createElement("thead"); table.appendChild(b);
var c1 = document.createElement("th"); c1.appendChild(document.createTextNode("Категория")); b.appendChild(c1);
var c2 = document.createElement("th"); c2.appendChild(document.createTextNode("Вес в условных единицах"));  b.appendChild(c2);
for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
{
	var cat = document.createElement("tr");
	cat.className = "cat"+catnum;
	var atd = document.createElement("td");
	var a = document.createElement("input"); a.type = "text" ; a.value = jQuery("#AnswerCategories input")[i].value;
	atd.appendChild(a);
	var btd = document.createElement("td");
	var b = document.createElement("input"); b.type = "text" ; b.value = jQuery("#AnswerCategories input")[i+1].value;
	b.className = "number-field";
	btd.appendChild(b);
	cat.appendChild(atd);cat.appendChild(btd);
	table.appendChild(cat);
	catnum+=1;
}
answer.appendChild(table);
var y = document.createElement("br");
jQuery("#AnswerList")[0].appendChild(answer);
jQuery("#AnswerList")[0].appendChild(y);
ansnum +=1;
}
function createYesNo()
{
	ClearAllAnswers();
	jQuery("#radioquestion")[0].checked =true;
	var temp=[];
		for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
		{
		temp[i]= jQuery("#AnswerCategories input")[i+1].value;
		}	
		
	if (jQuery("#yespoint")[0].checked == true)
	{
		jQuery("#AnswerText")[0].value = "Да";
		
		createAnswer();
		jQuery("#AnswerText")[0].value = "Нет";
		for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
		{
		 jQuery("#AnswerCategories input")[i+1].value = 0;
		}
		createAnswer();
		// restore category values
		for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
		{
		jQuery("#AnswerCategories input")[i+1].value=temp[i];
		}
	}
	else
	{
		for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
		{
		 jQuery("#AnswerCategories input")[i+1].value = 0;
		}
		jQuery("#AnswerText")[0].value = "Да";
		createAnswer();
		
		for (var i =0; i < jQuery("#AnswerCategories input").length; i = i + 2)
		{
		 jQuery("#AnswerCategories input")[i+1].value = temp[i];
		}
		jQuery("#AnswerText")[0].value = "Нет";
		createAnswer();
	}
	
	jQuery("#yesnoquestion")[0].checked =true;
}
function createScale()
{
	ClearAllAnswers();
	var min = jQuery("#min")[0].value;
	var max = jQuery("#max")[0].value;
    jQuery("#radioquestion")[0].checked =true;
		for (var i = parseInt(min); i < parseInt(max) + 1; i++)
		{
			jQuery("#AnswerText")[0].value = i;
			for (var j =0; j < jQuery("#AnswerCategories input").length; j = j + 2)
			{
			jQuery("#AnswerCategories input")[j+1].value = i;
			}
			createAnswer();
		}
	jQuery("#scalequestion")[0].checked =true;
}
function deleteAnswer()
{
 var x = jQuery("#AnswerList > div").last().nextAll("br");
 x.remove();
var x = jQuery("#AnswerList > div").last();
 x.remove();
 if (ansnum > 0)
 {
 ansnum = ansnum - 1;
 }

 
}
function ClearAllAnswers()
{
	jQuery("#AnswerList").children().remove();
	jQuery("#AnswerText")[0].value = ""; 
		ansnum = 0;	
}	
function finish(path,postpath)
{
	var x = document.createElement("input");
	x.type="submit";
	x.value="Отправить";
	
	var z = document.createElement("input");
	z.type="hidden";
	z.name="action";
	z.value = "kv_test_submit"; //processing function is function kv_test_submit
	
	var y = document.createElement("input");
	y.type="hidden";
	y.name="testname";
	y.value = jQuery("#testname")[0].value; 
	
	jQuery("#newform")[0].appendChild(y);
	jQuery("#newform")[0].appendChild(z);
	jQuery("#newform")[0].appendChild(document.createElement("br"));
	jQuery("#newform")[0].appendChild(x);
	
	jQuery("#newform")[0].method="POST";
	jQuery("#newform")[0].action=postpath; // admin-post
	
	var code =  jQuery("#newformcode")[0].innerHTML;
	jQuery.ajax({
            url: path,
            type: "post",
            data: {	action: "finishTestDB", // for ajax hook; refer to main php file
					testname: jQuery("#testname")[0].value,
					htmlcode : code
					},
			success: function(result)
			{
				alert(result);
				window.location.reload(); 
			}
        });
	questionnum = 1;
	jQuery("#newform")[0].innerHTML = "";
}