<?php

$current_user = query_user($current_user_id);
$current_user_name = $current_user['Fname']." ".$current_user["Lname"];

echo "
<button class='toggler'></button>
<div class='menu_wrapper'>
    <div class='menu_header'>
        <div class='menu_image'></div>
        <div class='menu_user'>
            <span>Logged in as:</span>
            <br>
            <b>$current_user_name</b>
            <span>$current_user_id</span>
        </div>
    </div>
    <ul class='menu_body'>
        <li><a href='homepage.php'>Home</a></li>
        <li><a href='user_details.php?user=$current_user_id'>User Profile</a></li>
        <li><a href='committee_selection.php'>Commitees</a></li>
        <li><a href='election_selection.php'>Elections</a></li>
        <li><a href='notifications.php'>Notifications</a></li>".
        (in_array($current_user_permissions, array("Admin", "Super")) ? "<li><a href='user_selection.php'>All Users</a></li>" : "")
."    </ul>
</div>

<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>
<script type='text/javascript'>
    $(document).ready(function() {
        $('.toggler').click(function(){
            $(this).toggleClass('toggled');
            $('.menu_wrapper').toggleClass('shown');
        })
    });
</script>
";
?>
