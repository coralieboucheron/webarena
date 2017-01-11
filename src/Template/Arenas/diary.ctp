<?php $this->assign('title', 'Diary');?>

<html>
    <head>
        <title> Diary </title>
        <meta charset="utf-8" />
    </head>
    <body>
        <section>
        <?php   
            date_default_timezone_set('Europe/Paris');
            $date1 = new DateTime('now');
            $x = $date1->format('Y-m-d H:i:s'); //get the actual time
                
            echo "Your fighter : x=".$myfighter->coordinate_x;
            echo " ; y=".$myfighter->coordinate_y;
            echo '<table class="diary">';
                echo '<tr>';
                    echo '<th>Event</th>';
                    echo '<th>Time</th>';
                    echo '<th>Position</th>';
                echo '</tr>';
                foreach($event_list as $e)
                {
                    $datetime1 = new DateTime($e->date);
                    $x = $datetime1->format('Y-m-d H:i:s'); //get the time of the event
                    $diff = date_diff($date1, $datetime1,true); //compare actual time and event time
                    if($diff->y==0 && $diff->d==0) //display events less than 24 hours ago
                    {
                        echo '<tr>';
                            echo '<td>';
                                echo $e->name;
                            echo '</td>';
                            echo '<td>';
                                echo $diff->h." h ".$diff->i." mn ago";
                            echo '</td>';
                            echo '<td>';
                                echo "x=".$e->coordinate_x;
                                echo " ; y=".$e->coordinate_y;
                            echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '</table>';
            ?>
        </section>
    </body>
</html>
