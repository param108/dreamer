function addNewRoleUpdate(event, ui) {
	console.log(event);
	console.log(ui);
}
function addNewRoleCb(id,text) {
	$('#sortable').append('<li class="ui-state-highlighted" dreamid="'+id+'">'+text+'</li>');
}
function addNewRole() {
	var newRole = $("#dream-text").val();

	if (typeof addNewRole.nextId == "undefined") {
		addNewRole.nextId = 0;
	}

	var newRoleId = ++addNewRole.nextId;
	// fire an Ajax and on response add
	addNewRoleCb(newRoleId, newRole);
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
	var lenby2 = n.length/2;
	var f = [];	
	var oldi = 0;
	var i = 0;
	var sum = 0;
	for (i = 1;;i+=2) {
		var t = n.slice(oldi,oldi+i);
		f = f.concat(createSphereList(t),[{role: '<br>'}]);
		oldi = oldi + i;
		if (oldi > lenby2) {
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
		f = f.concat(createSphereList(t),[{role:'<br>'}]);
		oldi = oldi + i;
		if (oldi >= n.length - 1) {
			break;
		}
	}
	f.push(n[n.length - 1]);
	return f;
}
function renderList(l) {
	var spherlist = organizeList(l);
	for (var i = 0; i < spherlist.length; i++) {
		var role = spherlist[i];
		if (role.role != '<br>') {
			$('#sortable').append('<li class="ui-state-default">'+role.role+'</li>');
		} else {
			$('#sortable').append('<br>');
		}
	}
}
$(document).ready(function() {
	$("#add-role-form").submit(addNewRole);	
	var rolesList = [{t_elapsed: 10, role: 'Father'},
			{t_elapsed: 1, role: 'Gamer'},
			{t_elapsed: 19, role: 'Artist'},
			{t_elapsed: 15, role: 'GrandFather'},
			{t_elapsed: 35, role: 'Cook'},
			{t_elapsed: 40, role: 'Worker'},
			{t_elapsed: 10, role: 'Fitness Trainer'}];
	renderList(rolesList);
});
