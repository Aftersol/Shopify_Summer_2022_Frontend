function query(strStartDate, strEndDate)
{
    document.querySelector("#loading-text").innerHTML = "Please wait . . .";
    document.querySelector("#viewport").innerHTML = "";

    startDate = Date.parse(strStartDate);
    endDate = Date.parse(strEndDate);

    /* Check if an object is an date */
    if (isNaN(startDate) || isNaN(endDate))
    {
        document.querySelector("#loading-text").innerHTML = "Please enter a vaild date";
        return;
    }

    if (startDate > endDate)
    {
        document.querySelector("#loading-text").innerHTML = "The start date be later than the end date";
        return;
    }

    launchDate = new Date(Date.UTC(2015,2,15,23,03,42));
    today = new Date(Date.now())

    if (startDate < launchDate)
    {
        startDate = launchDate;
    }

    if (endDate > today)
    {
        endDate = today;
    }
    
    try
    {
        /*fetch("query.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },body: `startDate=${strStartDate}&endDate=${strEndDate}`
        })
        .then((response) => response.text())
        .then((res) => (document.getElementById("viewport").innerHTML = res));*/

        fetch("test.php",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },body: `startDate=${strStartDate}&endDate=${strEndDate}`
        })
        .then((response) => response.text())
        .then((res) => (document.querySelector("#viewport").innerHTML = res));

    }
    catch
    {
        document.querySelector("#loading-text").innerHTML = "Cannot fetch from server";
        return;
    }

    /*document.getElementById("loading-text").innerHTML = "";

    var boxes = document.getElementsByClassName("box");

    for (var box = 0; box < boxes.length; box++)
    {
        if (localStorage.getElementById("date") = true)
        {
            boxes[box].getElementById("like").innerHTML = "Liked";
        }
        else
        {
            boxes[box].getElementById("like").innerHTML = "Like";
        }
    }*/
}

window.addEventListener("load", function()
{
    document.getElementById("query-form").addEventListener("submit",function(form){

        form.preventDefault();
        var passedStartDate = document.getElementById('startDate').value;
        var passedEndDate = document.getElementById('endDate').value;

        query(passedStartDate, passedEndDate);
    });

    var boxes = document.getElementsByClassName("box");
    for (var like = 0; like < boxes.length; like++)
    {
        console.log(boxes[like], like);

        var date = boxes[like].querySelector("#date").innerHTML;
        console.log(date);

        boxes[like].querySelector("#like").addEventListener("submit", function (likeButton) {
            if (localStorage.getItem(date, false))
            {   
                localStorage.setItem(date, true);
                boxes[like].querySelector("#like").innerHTML = "Liked";

            }
            else
            {
                localStorage.setItem(date, false);
                boxes[like].querySelector("#like").innerHTML = "Like";
            }
        });
    }
})
