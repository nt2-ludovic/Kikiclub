const onPageLoad = () =>
{

}
const loadStars = (workshop,container) => {
	let stars = document.createElement('div');
		stars.setAttribute('class','stars');
		stars.innerHTML = "Difficulty :";

	for (let i = 1; i <= 3; i++) {
		let star = document.createElement('span');
		if(i <= workshop["ID_DIFFICULTY"])
		{
			star.setAttribute('class','fa fa-star checked');
		}
		else
		{
			star.setAttribute('class','fa fa-star');
		}
		stars.appendChild(star);
	}
	container.appendChild(stars);
}
const loadMedia = (workshop,container ) => {

	let media = document.createElement("div");
		media.setAttribute('class','media');


		if(workshop["MEDIA_TYPE"] == "mp4")
		{
			let video = document.createElement('video');
				video.width = '100%';
				video.height = '100%';
				video.controls ='controls';
				video.innerHTML = "Your browser does not support the video tag.";

				let source = document.createElement('source');
				source.src = workshop["MEDIA_PATH"];
				source.type = "video/" + workshop["MEDIA_TYPE"];
				video.appendChild(source);

			media.appendChild(video);

		}
		else if(workshop["MEDIA_TYPE"] == "png" ||
				workshop["MEDIA_TYPE"] == "jpg")
		{
			let image = document.createElement('img');
				image.src = workshop["MEDIA_PATH"];

			media.appendChild(image);
		}
		else if(workshop["MEDIA_TYPE"] == "mp3")
		{
			let audio = document.createElement('audio');
			audio.src = workshop["MEDIA_PATH"];
			audio.controls = 'controls';
			audio.innerHTML = "Your browser does not support the audio element.";

			media.appendChild(audio);
		}

	container.appendChild(media);
  }
const hideWorkshop = (container) =>
{
	removeHeightToContainer(container);

}
const showWorkshop = (id,element) =>
{
	let previousContainers = document.querySelectorAll(".workshops-info");
	console.log(previousContainers);
	if(previousContainers.length > 0)
	{
		previousContainers.forEach(element => {
			console.log(element);
			hideWorkshop(element);
		});
	}
	let container = document.createElement('div');
		container.setAttribute('class', "workshops-info col-sm-12");

	container.style.height = 0;
	container.innerHTML = "";
	container.style.display = "block";
	container.setAttribute('onclick','hideWorkshop(this)');

	element.parentNode.parentNode.insertBefore(container,element.parentNode.nextSibling);
	element.parentNode.style.display = "none";
	console.log(element);

	let formData = new FormData();
		formData.append('id', id);
		fetch("workshops-ajax.php", {
			method: "POST",
			credentials: 'include', // Pour envoyer les cookies avec la requête!
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			let workshop = data["workshop"];
			let robots =  data["robots"];

			console.log(data);

			let title = document.createElement("h2");
			title.innerHTML = workshop.NAME;

			let robot = document.createElement("h4");
			robot.innerHTML = robots[workshop.ID_ROBOT].NAME;

			let content = document.createElement("p");
			content.innerHTML = workshop.CONTENT;

			container.appendChild(title);
			container.appendChild(robot);
			container.appendChild(content);

			loadStars(workshop,container);
			loadMedia(workshop,container);

			addHeightToContainer(container);
		})


}

const removeHeightToContainer= (container) =>
{

	if(container.offsetHeight >= 10)
	{
		let newHeight = container.offsetHeight - 1;
		container.style.height = newHeight + "px";
		setTimeout(()=>removeHeightToContainer(container),1);
	}
	else
	{
		container.innerHTML = "";
		container.style.display = "none";
		container.previousSibling.style.display = "block";
		container.parentNode.removeChild(container);
	}

}

const addHeightToContainer= (container) =>
{
	if(container.offsetHeight < 300)
	{
		let newHeight = container.offsetHeight + 1;
		container.style.height = newHeight + "px";
		setTimeout(()=>addHeightToContainer(container),1);
	}

}