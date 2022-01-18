async function sendPHPRequest(strStartDate,strEndDate)
{
	return 	fetch("query.php",
	{
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
		},body: `startDate=${strStartDate}&endDate=${strEndDate}`
	})
	.then((response) => response.text())
	.then((res) => (document.querySelector("#viewport").innerHTML = res));
}

async function query(strStartDate, strEndDate)
{
    document.querySelector("#loading-text").innerHTML = "Please wait . . .";
    document.querySelector("#viewport").innerHTML = "";

    var startDate = Date.parse(strStartDate);
    var endDate = Date.parse(strEndDate);

    /* Check if an object is an date */
    if (isNaN(startDate) || isNaN(endDate))
    {
        document.querySelector("#loading-text").innerHTML = "Please enter a vaild date";
        return;
    }

    if (startDate > endDate)
    {
        document.querySelector("#loading-text").innerHTML = "The start date cannot be later than the end date";
        return;
    }

    var launchDate = new Date(Date.UTC(2015,2,15));
    var today = new Date(Date.now())

    if (startDate < launchDate)
    {
        startDate = launchDate;
    }
    if (startDate > today)
    {
        document.querySelector("#loading-text").innerHTML = "You cannot search for images beyond today";
		return;
    }
    if (endDate > today)
    {
        endDate = today;
    }
    
	await sendPHPRequest(strStartDate,strEndDate);

    document.querySelector("#loading-text").innerHTML = "";
	
	var boxes = document.getElementsByClassName("box");
    for (var like = 0; like < boxes.length; like++)
    {
        console.log(boxes[like], like);

        var date = boxes[like].querySelector("#date").innerHTML;
        console.log(date);

		if (localStorage.getItem(date) === "false" || localStorage.getItem(date) === null)
		{   
			boxes[like].querySelector("#like").innerHTML = "Like";
		}
		else
		{
			boxes[like].querySelector("#like").innerHTML = "Liked";
		}
    }
}

function saveLikes(myDate)
{
	var boxes = document.getElementsByClassName("box");
    for (var like = 0; like < boxes.length; like++)
    {

        var date = boxes[like].querySelector("#date").innerHTML;
		if (date === myDate)
		{
			var hasBeenLiked=localStorage.getItem(date);
			if (localStorage.getItem(date) === "false" || localStorage.getItem(date) === null)
			{   
				localStorage.setItem(date, true);
				boxes[like].querySelector("#like").innerHTML = "Liked";

			}
			else
			{
				localStorage.setItem(date, false);
				boxes[like].querySelector("#like").innerHTML = "Like";
			}		
			
			break;
		}
    }
}

window.addEventListener("load", function()
{
    document.getElementById("query-form").addEventListener("submit",function(form){

        form.preventDefault();
        var passedStartDate = document.getElementById('startDate').value;
        var passedEndDate = document.getElementById('endDate').value;

        query(passedStartDate, passedEndDate);
    });


})
