<?php
// PRINTS A BACK BUTTON
# location is the link to go to
# "variables" specifies GET variables to set, if any
function print_back_button(string $message, string $previous_location, array $variables = array()) {
    $button_string = "<a href='$previous_location?";
        foreach ($variables as $variable => $value) {
            $button_string .= "$variable=$value";
        }
    $button_string .= "'>
            <div class='back'>
                < &nbsp; $message
             </div>
        </a>";
    echo $button_string;
}
?>