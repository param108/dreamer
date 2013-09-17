function addNewRole() {
	var newRole = $("#dream-text").val();
	$.post("ajax/addRole.php", { name: newRole}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
			$("#dream-text").val('');
			$.post("ajax/getRoles.php", renderList);
		}
	});
	return false;
}

// Presently the delete button next to search does nothing
function deleteRole() {
	return false;
}

// If there is only one selected choose it
function selectRole() {
	var word=$('#dream-text').val();
	var found = null;
	var foundsofar = 0;
	var r = new RegExp(word,'i');	
	for (var i = 0; i< _RoleData.length;i++) {
		if (r.test(_RoleData[i].name)) {
			if (!foundsofar) {
				found = $('li[roleid="'+_RoleData[i].roleid+'"]').find("a");	
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
// sort the roles on time-elapsed descending
function sortRoles(a,b) {
	return a.t_elapsed - b.t_elapsed;	
}

// reorders l!
function createSphereList(l) {
	l.sort(sortRoles);
	var ap = 1;
	var result = [];
	for (var i= 0; i<l.length;i++) {
		var role = l[i];
		if (ap) {
			result.push(role);
			ap = 0;
		} else {
			var r = [role];
			result = r.concat(result);
			ap = 1;
		}
	}
	return result;
}

function organizeList(l) {
	var n = createSphereList(l);
	// We will always keep a single at the end.
	var lenby2 = (n.length - 1)/2;
	var f = [];	
	var oldi = 0;
	var i = 0;
	var sum = 0;
	var br = [{'name':'<br>'}];
	for (i = 1;;i+=2) {
		var t = n.slice(oldi,oldi+i);
		f = f.concat(createSphereList(t),br);
		oldi = oldi + i;
		if (oldi >= lenby2) {
			break;
		}
	}	
	for (;i>0;i-=2) {
		var end = 0;
		// we want to keep a single at the end
		if (oldi + i <= (n.length - 1)) {
			end = oldi + i;
		} else {
			end = n.length - 1;
		}
		var t = n.slice(oldi,end);
		f = f.concat(createSphereList(t),br);
		oldi = oldi + i;
		if (oldi >= n.length - 1) {
			break;
		}
	}
	f.push(n[n.length - 1]);
	return f;
}
var _RoleData;
function renderList(l) {
	if (typeof l == "string") {
		l = $.parseJSON(l);
	}
	_RoleData = l;
	var spherlist = organizeList(l);
	if (spherlist) {
			$('#sortable').empty();
	}
	for (var i = 0; i < spherlist.length; i++) {
		var role = spherlist[i];
		if (role.name != '<br>') {
			if ($('#sortable-delete').length > 0) {
				$('#sortable-delete').append('<li class="ui-state-default" roleid="'+role.roleid+'"><a class="rolebtn">'+role.name+'<img class="ul-x-btn" src="img/x.png"/></a></li>');
			} else if ($('#sortable-select').length > 0) {
				$('#sortable-select').append('<li class="ui-state-default" roleid="'+role.roleid+'"><a class="rolebtn">'+role.name+'</a></li>');
			} else if ($('#sortable-add').length > 0) {
				$('#sortable-select').append('<li class="ui-state-default" roleid="'+role.roleid+'">'+role.name+'</li>');
			}
		} else {
			$('#sortable').append('<br>');
		}
	}
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
function unmatchedRoles(r) {
	var result = [];
	for (var i = 0; i< _RoleData.length;i++) {
		if (!r.test(_RoleData[i].name)) {
			$('li[roleid="'+_RoleData[i].roleid+'"]').hide();	
		} else {
			$('li[roleid="'+_RoleData[i].roleid+'"]').show();	
		}	
	}
}

function searchRole(event) {
	var word=$('#dream-text').val();
	var r = new RegExp(word,'i');	
	unmatchedRoles(r);
}

function selectRoleBtnClicked(event) {
	var me = $(event.target);
	var roleid = me.attr('roleid');
	// in progress FIXME
	$.post("ajax/role.php", { name: newRole}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
			$("#dream-text").val('');
			$.post("ajax/getRoles.php", renderList);
		}
	});
	return false;

}

$(document).ready(function() {
	if ($('#sortable-add').length > 0) {
		$('.dream-btn').val('Add');
		$("#role-form").submit(addNewRole);	
	} else if ($('#sortable-delete').length > 0) {
		$('.dream-btn').val('Delete');
		$('.dream-btn').hide();
		$("#role-form").submit(deleteRole);	
		$(".rolebtn").submit(deleteRoleBtnClicked);	
	} else if ($('#sortable-select').length > 0) {
		$('.dream-btn').val('Select');
		$("#role-form").submit(selectRole);	
		$(".rolebtn").submit(selectRoleBtnClicked);	
	}
	$('#dream-text').keyup(searchRole);
	$.post("ajax/getRoles.php", renderList);
	//var rolesList = [{t_elapsed: 10, role: 'Father'},
	//		{t_elapsed: 1, role: 'Gamer'},
	//		{t_elapsed: 19, role: 'Artist'},
	//		{t_elapsed: 15, role: 'GrandFather'},
	//		{t_elapsed: 35, role: 'Cook'},
	//		{t_elapsed: 40, role: 'Worker'},
	//		{t_elapsed: 10, role: 'Fitness Trainer'}];
	//renderList(rolesList);
});
