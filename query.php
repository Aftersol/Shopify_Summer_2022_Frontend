<?php

#Pass both dates as string
function sendAPIQuery($strStartDate, $strEndDate)
{
    $htmlString = "";
    $launchDate = date("Y-m-d",strtotime("2015-02-11"));
    $today = gmdate("Y-m-d");
    if (!is_string($strStartDate) || !is_string($strEndDate))
    {
        echo "Invaild date submitted to server";
        return;
    }

    $startDate = date("Y-h-d", strtotime($strStartDate));
    $endDate = date("Y-h-d", strtotime($strEndDate));


    if ($startDate > $endDate)
    {
        echo "Start date must not be later than end date";
        return;
    }

    if ($startDate < $launchDate) # compare to DSCOVR's launch data to speed up calculations
    {
        $startDate = $launchDate;
    }

    if ($endDate > $today("Y-m-d")) # Make sure they don't go past today's
    {
        $endDate = $today("Y-m-d");
    }

    $currentDate = $startDate;

    while ($currentDate <= $endDate)
    {
        $year = strtotime('Y', $startDate);
        $month = strtotime('M', $startDate);
        $day = strtotime('D', $startDate);

        $metadata = "https://epic.gsfc.nasa.gov/api/natural/date/{$year}-{$month}-{$day}";
        $meta = file_get_contents($metadata);
        $arr = json_decode($meta);
    
        $counter = 1;
        foreach($arr as $item) {
            $name = $item->image.'.jpg';
            $description = $item->caption;
            $archive = "https://epic.gsfc.nasa.gov/archive/natural/{$year}/{$month}/{$day}/jpg/";
    
            $source = $archive.$name;
            $destination = "/epic/images/{$year}/{$month},{$day}".$name;
    
            if(!file_exists($destination)) # Do not download NASA Images if the server already has the images
                copy($source, $destination);    # Download and copy the image if the file doesn't exist in the server
    
            $htmlString .= <<<EOD
                "<div class="box">
                    <picture>
                        <img src="$destination">
                    </picture>
    
                    <div class="description">
                        <p id="date">${year}-${month}-${day} #{$counter}</p>
                        <p class="caption">${description}</p>
                    </div>
    
                    <div class="like-btn">
                        <button>Like</button>
                    </div>
                </div>"
                EOD;

            $counter = $counter + 1;
        }

        $currentDate = date("Y-m-d", strtotime($startDate. ' + 1 days'));
    }

    echo $htmlString;
}


$startDate = $_POST["startDate"];
$endDate = $_POST["endDate"];
sendAPIQuery($startDate , $endDate);

?>