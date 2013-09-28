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

$(document).ready(function() {

	// data will be formatted as
	// { futureId, size, photo, text } 
	/*var sampleData = [{ 
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
	];*/
	$.post("ajax/getFutures.php", function(data) {
			var memdata = $.parseJSON(data);
			createMemoryLayout(memdata,$('#memories'));
	}
})
