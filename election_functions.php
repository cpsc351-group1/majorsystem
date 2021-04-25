<?php
//  ELECTION FUNCTIONS

//  SELECT ELECTION DETAILS
function query_election(int $entered_id) {
    global $conn;
    global $election_id;
    global $committee_id;
    global $status;
    global $num_seats;

    # pull election information using $_GET
    $election_sql = "SELECT * FROM `Election` WHERE Election_ID=?";
    # prepare statement (this is done to prevent sql injection)
    $election = $conn->prepare($election_sql);
    # bind parameter to int
    $election->bind_param('i', $entered_id);
    # execute statement
    $election->execute();
    # bind results to variables
    $election->bind_result($election_id, $committee_id, $status, $num_seats);
    # fetch row and close
    $election->fetch();
    $election->close();
}

function query_committee(int $committee_id) {

    global $conn;
    global $committee;

    $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$committee_id'";
    $committee = $conn->query($committee_sql)->fetch_assoc();
}

//  GET NOMINEES LIST
# pulls the list of nominees for a given election
function query_election_nominees(int $election_id) {
    global $conn;
    global $noms;
    global $noms_count;

    $noms_sql = "SELECT * FROM `Nomination` WHERE Election_Election_ID = $election_id";
    $noms = $conn->query($noms_sql);
    $noms_count = $noms->num_rows;
}

//  GET VOTES INFO
# pulls the list of nominees for a given election
# and information about the current session user's votes
function query_election_votes(int $election_id) {
    global $conn;
    global $votes;
    global $votes_count;
    global $previous;
    global $current_user_id;

    # username
    $voter_id = $current_user_id;

    # pull previous vote in this election
    $votes_sql = "SELECT * FROM `Vote` WHERE Election_Election_ID = '$election_id'";

    $votes = $conn->query($votes_sql);
    $votes_count = $votes->num_rows;

    $previous = NULL;
    while ($row = $votes->fetch_assoc()) {
        if ($row['Voter_CNU_ID'] == $voter_id) {
            $previous = $row['Votee_CNU_ID'];
            break;
        }
    }
}

//  PRINT NOMINEES TABULARLY
# prints nominee tiles or an error message if there are none.
function print_nominees()
{
    global $conn;
    global $election_id;
    global $status;
    global $noms;
    global $previous;

    # pull nomination information for given election
    query_election_nominees($election_id);

    query_election_votes($election_id);

    # error message if there are no nominees
    if ($noms->num_rows < 1) {
        echo "<div class='center'>No nominations have been submitted for this election.</div>";
    } else {
        # for each nominee ...
        while ($row = $noms->fetch_assoc()) {
            # get nominee information ...
            $nominee_id = $row['Nominee_CNU_ID'];
            $user = query_user($nominee_id);

            $user_name = $user['Fname']." ".$user['Lname'];

            $user_info = array(
                        'Department'=>$user['Department'],
                        'Position'=>$user['Position'],
                        'Race'=>$user['Race'],
                        'Gender'=>$user['Gender']);

            // voter status, if applicable (in voting status)
            // detect if user is same as previously voted
            $checked = $nominee_id == $previous;

            # ... and print tiles
            echo "<div class='tile".($checked?" voted":"")."'>";

            // render disabled radio badges to show vote
            if ($status=='Voting') {
                echo "<input type='radio' disabled='disabled' ".($checked?'checked':'').">";
            }

            echo "<span class='heading sub'>$user_name</span>";
            echo "<div class='list ruled'>";
            // user information
            foreach ($user_info as $label => $detail) {
                echo "<div>$label</div> <div>$detail</div>";
            }
            echo "</div></div>";
        }
    }
}
?>