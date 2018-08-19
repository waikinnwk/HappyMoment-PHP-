<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php require_once 'commonHeader.php'; ?>
	<script type="text/javascript">
	 function openForm(classId){
		window.open("http://" + "<?php echo $_SERVER['HTTP_HOST']."/applyClass.php?eventId=";?>" + classId);
	 }
	 
	 function pMonth(curY,curM){
		var m = curM - 1;
		var y = curY;
		if(m == 0){
			m = 12;
			y = y - 1;
		}
		window.location.href = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/application.php?year=";?>" + y + "&month=" + m;
	 }
	 
	 function nMonth(curY,curM){
	 	var m = curM + 1;
		var y = curY;
		if(m > 12){
			m = 1;
			y = y + 1;
		}
		window.location.href = "http://" + "<?php echo $_SERVER['HTTP_HOST']."/application.php?year=";?>" + y + "&month=" + m;
	 }
	 </script>
</head>
<body>
<?php 
	require_once 'cMenuBar.php'; 
	/* draws a calendar */
	function draw_calendar($month,$year){
	
		

		/* draw table */
		$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

		/* table headings */
		$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$calendar.= '<tr class="calendar-row-head"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

		/* days and weeks vars now ... */
		$running_day = date('w',mktime(0,0,0,$month,1,$year));
		$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = array();

		/* row for week one */
		$calendar.= '<tr class="calendar-row">';

		/* print "blank" days until the first of the current week */
		for($x = 0; $x < $running_day; $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		endfor;

		/* keep going with days.... */
		for($list_day = 1; $list_day <= $days_in_month; $list_day++):
			$calendar.= '<td class="calendar-day">';
				/* add in the day number */
				$calendar.= '<div class="day-number">'.$list_day.'</div>';

				/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
				$calendar.= str_repeat('<p> </p>',2);
				$calendar.='<div class="calendar-event-container">';
				$calendarEventList = getClassCalendar($year,$month,$list_day);
				
				$cMonth = date("n");
				$cYear = date("Y");
				$cDay = date("j");
				
				$dimEvent = false;
				if($cYear > $year)
					$dimEvent = true;
				else if($cYear == $year){
					if($cMonth > $month)
						$dimEvent = true;
					else if($cMonth == $month){
						if($cDay > $list_day)
							$dimEvent = true;					
					}
				}
				
				while($event = mysqli_fetch_array($calendarEventList)){
					if($dimEvent)
						$calendar.= '<div class="calendar-event-dim">'.$event['CE_TIME'].'&nbsp;&nbsp;&nbsp;'.$event['CE_TITLE'].'</div>';
					else
						$calendar.= '<div class="calendar-event" onclick="openForm('."'".$event['CE_ID']."'".')">'.$event['CE_TIME'].'&nbsp;&nbsp;&nbsp;'.$event['CE_TITLE'].'</div>';
				}
				
				$calendar.='</div>';
			$calendar.= '</td>';
			if($running_day == 6):
				$calendar.= '</tr>';
				if(($day_counter+1) != $days_in_month):
					$calendar.= '<tr class="calendar-row">';
				endif;
				$running_day = -1;
				$days_in_this_week = 0;
			endif;
			$days_in_this_week++; $running_day++; $day_counter++;
		endfor;

		/* finish the rest of the days in the week */
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;

		/* final row */
		$calendar.= '</tr>';

		/* end the table */
		$calendar.= '</table>';
		
		/* all done, return result */
		return $calendar;
	}
	
	
	
	
	if(isset($_GET['year']) && isset($_GET['month']) ){
		$month = $_GET['month'];
		$year = $_GET['year'];
	}
	else{
		$month = date("n");
		$year = date("Y");
	}
	
	$dateObj   = DateTime::createFromFormat('!m', $month);
	$monthName = $dateObj->format('F'); 
	
	
	echo '<div class="notebox">';
	echo "<div align='center'>";
	echo "<table><tr><td style='vertical-align:middle;color: blue;cursor:pointer;' onclick='pMonth(".$year.",".$month.");'>&laquo;Previous</td><td><h2>&nbsp;&nbsp;&nbsp;".$monthName."  ".$year."&nbsp;&nbsp;&nbsp;</h2></td><td style='vertical-align:middle;color: blue;cursor:pointer;' onclick='nMonth(".$year.",".$month.");'>Next&raquo;</td></tr></table>";
	echo "<div>";
	echo draw_calendar($month,$year);
	echo '</div>';
?>


</body>
</html>