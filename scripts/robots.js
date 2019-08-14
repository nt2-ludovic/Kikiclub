const onPageLoad = () =>
{
	Dropzone.autoDiscover = false;
	$('#mixedSlider').multislider({
		interval:0,
	});


		// document.getElementById('btn-right').onmouseenter = ()=>{$('#mixedSlider').multislider('continuous')}
		// document.getElementById('btn-right').onmouseout = ()=>{$('#mixedSlider').multislider('continuous')}

		let intervalRight;
		document.getElementById('btn-right').onmouseenter = ()=>{
			intervalRight = setInterval(() => { $('#mixedSlider').multislider('next') }, 500);
		}
		document.getElementById('btn-right').onmouseout = ()=>{
			clearInterval(intervalRight);
		}

	let intervalLeft;

	document.getElementById('btn-left').onmouseenter = ()=>{
		intervalLeft = setInterval(() => { $('#mixedSlider').multislider('prev') }, 500);
	}
	document.getElementById('btn-left').onmouseout = ()=>{
		clearInterval(intervalLeft);
	}
}
function openModal() {
	document.getElementById("robot_modal").style.display = "block";
}
function closeModal() {
	document.getElementById("robot_modal").style.display = "none";
}

// $action->grades[$action->robot["id_grade"]]["name"]
const loadRobotInfos = (container,robot,robot_grade) =>
{
	let nameH3 = document.createElement('h3');
		nameH3.innerHTML = robot["name"];

	let gradeH4 = document.createElement('h4');
		gradeH4.innerHTML = "Recommanded Grade : " + robot_grade["name"];

	let descDiv = document.createElement('div');
		descDiv.setAttribute('class','description');
		descDiv.innerHTML = robot["description"];

	let mediaDiv = document.createElement('div');
		mediaDiv.setAttribute('class','media');

		let mediaImg = document.createElement('img');
			mediaImg.src= robot["media_path"];


		mediaDiv.appendChild(mediaImg);

	container.appendChild(nameH3);
	container.appendChild(gradeH4);
	container.appendChild(descDiv);
	container.appendChild(mediaDiv);
}
