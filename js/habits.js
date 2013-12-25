function addNewHabit() {
	var newHabit = $("#habit-text").val();
	newHabit = newHabit.replace(/\s+/g, ' ');
	newHabit = newHabit.replace(/^\s+|\s+$/g, '');
	if (!((newHabit == '')||(newHabit == ' ')||(habitExists(newHabit)))) {
		$('.loader').show();
		$.post("ajax/addHabit.php", { name: newHabit}, function (data) {
			var out = $.parseJSON(data);
			$("#habit-text").val('');
			$.post("ajax/getHabits.php", renderList);
		});
	} else {
		$("#habit-text").val('');
		searchRole(null);
		//FIXME add a dialog here
	}
	return false;
}

function deleteHabit() {
	var word=$('#habit-text').val();
	var found = null;
	var foundsofar = 0;
	var r = new RegExp(word,'i');	
	for (var i = 0; i< _HabitData.length;i++) {
		if (r.test(_HabitData[i].name)) {
			if (!foundsofar) {
				found = $('li[habitid="'+_HabitData[i].habitid+'"]').find("a");	
				foundsofar++;
			} else {
				return false;
			}
		}	
	}

	if (found !== null) {
		found.click();
	}
	return false;
}

// If there is only one selected choose it
function selectHabit() {
	var word=$('#habit-text').val();
	var found = null;
	var foundsofar = 0;
	var r = new RegExp(word,'i');	
	for (var i = 0; i< _HabitData.length;i++) {
		if (r.test(_HabitData[i].name)) {
			if (!foundsofar) {
				found = $('li[habitid="'+_HabitData[i].habitid+'"]').find("a");	
				foundsofar++;
			} else {
				return false;
			}
		}	
	}

	if (found !== null) {
		found.click();
	}
	return false;
}

function convertEaseToInt(a) {
	switch(a) {
		case 'auto': return 0;	
		case 'easy': return 1;	
		case 'hard': return 2;	
        }

}
// sort the roles 
function sortHabits(a,b) {
	var al = convertEaseToInt(a.ease);
	var bl = convertEaseToInt(b.ease);
        // we want the easy habits at the end
	var diff = al - bl;
	if (diff == 0) {
		return b.t_elapsed - a.t_elapsed;	
        } else {
		return diff;
	}
}

function organizeList(l) {
	l.sort(sortHabits);
	return l;
}
var _HabitData;

function habitExists(word) {
	var testWord = word.toLowerCase();
	for (var i = 0; i < _HabitData.length; i++) {
		if (testWord == _HabitData[i].name.toLowerCase()) {
			return true;
		}	
	}	
	return false;
}

function renderList(l) {
	if (typeof l == "string") {
		if (l != '') {
			l = $.parseJSON(l);
		} else {
			_HabitData = [];
			return;
		}
	}
	_HabitData = l;
	var spherlist = organizeList(l);
	if (spherlist) {
			$('#sortable-delete').empty();
			$('#sortable-select').empty();
			$('#sortable-add').empty();
	}
	var pageType="delete";
	if ($('#sortable-delete').length > 0) {
				pageType = "delete";
	} else if ($('#sortable-select').length > 0) {
				pageType = "select";
	} else if ($('#sortable-add').length > 0) {
				pageType = "add";
	}
	
	for (var i = 0; i < spherlist.length; i++) {
		var habit = spherlist[i];
		switch (pageType) {
			case 'delete':
			$('#sortable-delete').append('<li class="ui-state-default" habitid="'+habit.habitid+'"><a class="habitbtn">'+habit.name+'<img class="ul-x-btn" src="img/x.png"/></a></li>');
			break;
			case 'select':	
			$('#sortable-select').append('<li class="ui-state-default" habitid="'+habit.habitid+'"><a class="habitbtn">'+habit.name+'</a>+habit.score+'/'+habit.days</li>');
			break;
			case 'add':
			$('#sortable-add').append('<li class="ui-state-default" habitid="'+habit.habitid+'">'+habit.name+'</li>');
			break;
		}

		$('#sortable-delete').append('<br>');
		$('#sortable-select').append('<br>');
		$('#sortable-add').append('<br>');
	}

	switch (pageType) {
		case "delete":
			$(".habitbtn").unbind("click");
			$(".habitbtn").click(deleteHabitBtnClicked);
			$(".ul-x-btn").click(deleteHabitBtnXClicked);
		break;
		case "select":
			$(".habitbtn").unbind("click");
			$(".habitbtn").click(selectHabitBtnClicked);
		break;
		case "add":
		break;
	}
	$('.loader').hide();
}

function isalpha(c) {
	return (((c >= 97) && (c <= 122)) || ((c >= 65) && (c <= 90)));
}

function isdigit(c) {
	return ((c >= 48) && (c <= 57));
}

function isalnum(c) {
	return (isalpha(c) || isdigit(c));
}

// r is a regexp
function unmatchedHabits(r) {
	var result = [];
	for (var i = 0; i< _HabitData.length;i++) {
		if (!r.test(_HabitData[i].name)) {
			$('li[habitid="'+_HabitData[i].habitid+'"]').hide();	
		} else {
			$('li[habitid="'+_HabitData[i].habitid+'"]').show();	
		}	
	}
}

function searchHabit(event) {
	var word=$('#habit-text').val();
	var r = new RegExp(word,'i');	
	unmatchedHabits(r);
}

function selectHabitBtnClicked(event) {
	var target = event.target;
	var habitid = $(target).parent().attr('habitid');
	var url="habits.php?habitid="+habitid;
	window.location.assign(url);
	return false;
}

function deleteHabitBtnXClicked(event) {
	var target = event.target;
	var habitid = $(target).parent().parent().attr('habitid');
	$('.loader').show();
	$.post("ajax/deleteHabit.php", { habitid: habitid}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
			$("#habit-text").val('');
			$.post("ajax/getHabits.php", renderList);
		}
	});
	return false;
}

function deleteHabitBtnClicked(event) {
	var target = event.target;
	var habitid = $(target).parent().attr('habitid');
	$('.loader').show();
	$.post("ajax/deleteHabit.php", { habitid: habitid}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
			$("#habit-text").val('');
			$.post("ajax/getHabits.php", renderList);
		}
	});
	return false;
}

$(document).ready(function() {
	if ($('#sortable-add').length > 0) {
		$('.habit-btn').val('Add');
		$("#habit-form").submit(addNewHabit);	
	} else if ($('#sortable-delete').length > 0) {
		$('.habit-btn').val('Delete');
		$('.habit-btn').hide();
		$("#habit-form").submit(deleteHabit);	
		$(".habitbtn").click(deleteHabitBtnClicked);	
	} else if ($('#sortable-select').length > 0) {
		$('.habit-btn').val('Select');
		$("#habit-form").submit(selectHabit);	
		$(".habitbtn").click(selectHabitBtnClicked);	
	}
	$('#habit-text').keyup(searchHabit);
	$('.loader').show();
	$.post("ajax/getHabits.php", renderList);
	//var rolesList = [{t_elapsed: 10, role: 'Father'},
	//		{t_elapsed: 1, role: 'Gamer'},
	//		{t_elapsed: 19, role: 'Artist'},
	//		{t_elapsed: 15, role: 'GrandFather'},
	//		{t_elapsed: 35, role: 'Cook'},
	//		{t_elapsed: 40, role: 'Worker'},
	//		{t_elapsed: 10, role: 'Fitness Trainer'}];
	//renderList(rolesList);
});
