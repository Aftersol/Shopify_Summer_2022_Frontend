<?php

function errorCode($errorString)
{
	return "<p class='error-code'>".$errorString."</p>";
}

#Pass both dates as string
function sendAPIQuery($strStartDate, $strEndDate)
{
    $htmlString = "";
    $launchDate = date("Y-m-d",strtotime("2015-02-11"));
    $today = gmdate("Y-m-d");

    if (!is_string($strStartDate) || !is_string($strEndDate))
    {
        echo errorCode("Invaild date submitted to server");
        return;
    }

    $startDate = date("Y-m-d", strtotime($strStartDate));
    $endDate = date("Y-m-d", strtotime($strEndDate));


    if ($startDate > $endDate)
    {
        echo errorCode("Start date must not be later than end date");
        return;
    }
	
    if ($startDate > $today)
    {
        echo errorCode("The start date submitted to the server must not be searched beyond today's date");
        return;
    }

    if ($startDate < $launchDate) # compare to DSCOVR's launch data to speed up calculations
    {
        $startDate = $launchDate;
    }

    if ($endDate > $today) # Make sure they don't go past today's
    {
        $endDate = $today;
    }
	//echo $strStartDate.' '.$startDate.' '.$today;
    $currentDate = $startDate;

	$imageCounter = 0;
    while ($currentDate <= $endDate)
    {
		# Based on NASA example API Code for EPIC image search
        $year = date('Y', strtotime($currentDate));
        $month = date('m', strtotime($currentDate));
        $day = date('d', strtotime($currentDate));

        $metadata = "https://epic.gsfc.nasa.gov/api/natural/date/{$year}-{$month}-{$day}";
        $meta = file_get_contents($metadata);
        $arr = json_decode($meta);
    
        $counter = 1;
        foreach($arr as $item) {
			$imageCounter = $imageCounter + 1;
            $name = $item->image.'.jpg';
            $description = $item->caption;
            $archive = "https://epic.gsfc.nasa.gov/archive/natural/{$year}/{$month}/{$day}/jpg/";
    
            $source = $archive.$name;
            $destination = "epic/images/{$year}/{$month}/{$day}/".$name;
    
            if(!file_exists($destination))# Do not download NASA Images if the server already has the images
			{
				if (!file_exists("epic/images/{$year}/{$month}/{$day}"))
				{
					mkdir("epic/images/{$year}/{$month}/{$day}",0222,true);
				}
				copy($source, $destination);    # Download and copy the image if the file doesn't exist in the server
			}

    
            $htmlString .= <<<EOD
                <div class="box">
                    <picture>
                        <img src="$destination">
                    </picture>
    
                    <div class="description">
                        <p id="date">${year}-${month}-${day} #{$counter}</p>
                        <p class="caption">${description}</p>
                    </div>
    
                    <div class="like-btn">
                        <button id="like" onclick="saveLikes('${year}-${month}-${day} #{$counter}')" type="button">Like</button>
                    </div>
                </div>
                EOD;

            $counter = $counter + 1;
        }
		usleep(100000);

        $currentDate = date("Y-m-d", strtotime($currentDate. ' + 1 days'));
    }
	
	if ($imageCounter == 0)
	{
		echo errorCode("No images found from NASA's server");
	}
	else
	{
		echo $htmlString;
	}
    
}


$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];
sendAPIQuery($startDate , $endDate);

?>