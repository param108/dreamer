// The home page will show futures and a link to roles and challenges

// data is of the form { futureId, size = [small, medium, large], photo (url), text }
function createMemoryDiv(data) {
	var output = [
			'<div  futureId="%futureId%" class="%size% memory">', 
			'<div class="memory-photo">',
			'<img class="memory-photo-img" src="%photo%"></img>',
			'</div>',
			'<div class="memory-text">',
			'<p class="memory-text-span">',
			'%text%',
			'</p>',
			'</div>',
			'<div class="memory-buttons">',
			'<button type="button">Like!</button>',
			'</div>',
			'</div>'
		].join('\n').replace("%futureId%", data.futureId.toString()).
				replace("%photo%",data.photo).
				replace("%text%",data.text).
				replace("%size%",data.size);
	return output;
}

function createMemoryLayout(data, p) {
	for (var i = 0; i<data.length; i++) {
		p.append(createMemoryDiv(data[i]));
	}
}

function moveBadgesTabLeft() {
	console.log("left");
	$('#badges').animate({left: "-70%"},400, "swing",
		function () {
			$('#badges').unbind('click');
			$('#badges-tab').click(moveBadgesTabRight);
		}
	);
}
function moveBadgesTabRight() {
	console.log("right");
	$('#badges').animate({left: "0px"},400, "swing",
		function () {
			$('#badges-tab').unbind('click');
			$('#badges').click(moveBadgesTabLeft);
		}
	);
}
function moveQuestsTabUp() {
	console.log("left");
	$('#quests').animate({top: "-70%"},400, "swing",
		function () {
			$('#quests').unbind('click');
			$('#quests-tab').click(moveQuestsTabDown);
		}
	);
}
function moveQuestsTabDown() {
	console.log("right");
	$('#quests').animate({top: "0px"},400, "swing",
		function () {
			$('#quests-tab').unbind('click');
			$('#quests').click(moveQuestsTabUp);
		}
	);
}


$(document).ready(function() {

	// data will be formatted as
	// { futureId, size, photo, text } 
	var sampleData = [{ 
				futureId: 1,
				size: 'medium',
				photo: '450x450.png',
				text: 'This one is when I was creating the website'
			},
			{ 
				futureId: 1,
				size: 'small',
				photo: '300x300.png',
				text: 'This one is when I was creating the website'
			},
			{ 
				futureId: 1,
				size: 'large',
				photo: '600x600.png',
				text: 'This one is when I was creating the website'
			}
	];
	createMemoryLayout(sampleData,$('#memories'));
	/*$.post("ajax/getFutures.php", function(data) {
			var memdata = $.parseJSON(data);
			createMemoryLayout(memdata,$('#memories'));
	}*/


	$('#badges-tab').click(moveBadgesTabRight);
	$('#quests-tab').click(moveQuestsTabDown);
})
