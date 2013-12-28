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
		searchHabit(null);
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

function updateHabitData(habitid, ease) {
	for (var i = 0; i< _HabitData.length;i++) {
		if (_HabitData[i].habitid == habitid) {
			_HabitData[i].ease = ease;	
			_HabitData[i].score = (parseInt(_HabitData[i].score) + 1)+"";	
			$('li[habitid="' + habitid +'"]').find('.habit-expand-info').empty();
			$('li[habitid="' + habitid +'"]').find('.habit-expand-info').text('score:'+_HabitData[i].score+'/'+_HabitData[i].d_elapsed+' ease:'+_HabitData[i].ease);
			break;
		}
	}
}

function habitUpdateClick(event) {
	var habitid = $(this).attr('habitid');
	var ease = $(this).parents('li').find('.habit-update-ease-select').val();
        $('li[habitid="'+habitid+'"]').find('.habit-update-loader').show();
	$.post("ajax/updateHabit.php", { habitid: habitid, ease: ease}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
	            $('li[habitid="'+habitid+'"]').find('.habit-update-successfully-updated').show();
	            $('li[habitid="'+habitid+'"]').find('.habit-update-input').hide();
		    updateHabitData(habitid, ease);
		}
                $('li[habitid="'+habitid+'"]').find('.habit-update-loader').hide();
	});
}

function renderList(l) {
	if (typeof l == "string") {
		if (l != '') {
			l = $.parseJSON(l);
		} else {
			_HabitData = [];
			$('#sortable-delete').empty();
			$('#sortable-select').empty();
			$('#sortable-add').empty();
			$('.loader').hide();
			return;
		}
	}
	_HabitData = l;
	var spherlist = organizeList(l);
	$('#sortable-delete').empty();
	$('#sortable-select').empty();
	$('#sortable-add').empty();
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
			var easysel = '', autosel = '', hardsel = '';
			if (habit.ease == 'easy') {
				easysel = "selected";
			} else if (habit.ease == 'hard') {
				hardsel = "selected";
			} else if (habit.ease == 'auto') {
				autosel = "selected";
			}
			$('#sortable-select').append('<li class="sortable-select-li" habitid="'+habit.habitid+'">' +
	'<span><button class="habit-expand" type="button">' +
	'<img class="play-rt" src="img/prt.png"></img>' +
	'<img class="play-dn" src="img/pdn.png"></img></button>' +
	'<a class="habitbtn"><b>'+habit.name+'</b></a>'+
   	' <span class="habit-expand-info">score:'+habit.score+'/'+habit.d_elapsed+' ease:'+habit.ease+'</span></span><br>' +
	'<div class="habit-update-div">' +
        '<span class="habit-update-input">' +
        '<select class="habit-update-ease-select">' +
          '<option class="habit-update-ease-auto" value="auto" '+ autosel +'>Automatic</option>' +
          '<option class="habit-update-ease-easy" value="easy" '+ easysel +'>Easy</option>' +
          '<option class="habit-update-ease-hard" value="hard" '+ hardsel + '>Hard</option>' +
        '</select>' +
        '<button class="habit-update-button" habitid="'+habit.habitid+'" type="button">Update</button>' +
	'<img class="habit-update-loader" src="img/load.gif"></img>' +
        '</span>' +  
        '<span class="habit-update-already-updated"><i>You have already updated this today</i></span>' +
        '<span class="habit-update-successfully-updated"><i>You have successfully updated this</i></span>' +
	'</div>' +
	'</li>');
			break;
			case 'add':
			$('#sortable-add').append('<li class="ui-state-default" habitid="'+habit.habitid+'">'+habit.name+'</li>');
			break;
		}

		$('#sortable-delete').append('<br>');
		$('#sortable-select').append('<br>');
		$('#sortable-add').append('<br>');
	        if (habit.t_elapsed < 1) {
	            $('li[habitid="'+habit.habitid+'"]').find('.habit-update-already-updated').show();
	            $('li[habitid="'+habit.habitid+'"]').find('.habit-update-input').hide();
	        }

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
        $('.habit-expand').click(habitExpandClick);
        $('.habit-update-button').click(habitUpdateClick);
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
	var url="renderHabit.php?habitid="+habitid;
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

function habitExpandClick(event) {
        var target = event.target;
        var t1,t2;
        if (t2 = $(target).parent().find('.play-rt').toggle().is(':visible')) {
                $(target).parents('li').find('.habit-update-div').hide();
        }
        if (t1 = $(target).parent().find('.play-dn').toggle().is(':visible')) {
                $(target).parents('li').find('.habit-update-div').show();
        }
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
