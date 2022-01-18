<?php
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

    $startDate = date("Y-m-d", strtotime($strStartDate));
    $endDate = date("Y-m-d", strtotime($strEndDate));


    if ($startDate > $endDate)
    {
        echo "Start date must not be later than end date";
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

	echo $currentDate,$endDate,'<br>';
    while ($currentDate <= $endDate)
    {
		echo $currentDate,'<br>';
        $currentDate = date("Y-m-d",strtotime("+1 days",strtotime($currentDate)));
		#$currentDate = date("Y-m-d",strtotime($currentDate));
    }
}

$strStartDate = $_POST['startDate'];
$strEndDate = $_POST['endDate'];

sendAPIQuery($strStartDate, $strEndDate);
?>