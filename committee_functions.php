<?php
//  COMMITTEE FUNCTIONS

//  SELECT COMMITTEE DETAILS
# pulls a user's details based on a given ID -> committee assoc()

function query_committee($conn, int $entered_id) {
    global $committee_id;
    global $committee_name;
    global $committee_description;

    $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID=?";
    # prepare statement (to prevent mysql injection)
    $com_stmt = $conn->prepare($com_sql);
    # bind inputs
    $com_stmt->bind_param('i', $entered_id);
    # execute statement
    $com_stmt->execute();
    # bind results to variables
    $com_stmt->bind_result($committee_id, $committee_name, $committee_description);
    # fetch row and close
    $com_stmt->fetch();
    $com_stmt->close();
}

//  SELECT COMMITTEE CHAIR
# pulls committee chair for given committee -> committee_chair ID
function query_committee_chair($conn, int $committee_id) {
    $chair_sql = "SELECT User_CNU_ID FROM `Chairman` WHERE Committee_Committee_ID='$committee_id'";
    $chair = $conn->query($chair_sql);
    if ($chair -> num_rows > 0) {
        $chair_id = $chair->fetch_assoc()['User_CNU_ID'];
    } else {
        $chair_id = NULL;
    }
    return $chair_id;
}

//  SELECT COMMITTEE SEATS
# pulls committee seat info for given committee -> mysqli query object
function query_committee_seats($conn, int $committee_id) {
    $committee_seats_sql = "SELECT * FROM `Committee Seat` WHERE Committee_Committee_ID = '$committee_id' AND Ending_Term IS NULL;";
    $committee_seats = $conn->query($committee_seats_sql);
    return $committee_seats;
}

//  SELECT COMMITTEE ELECTION
# pulls committee election status -> election assoc()
function query_committee_election($conn, int $committee_id) {
    $election_sql = "SELECT * FROM `Election` WHERE Committee_Committee_ID='$committee_id' AND NOT Status='Complete'";
    $election = $conn->query($election_sql)->fetch_assoc();
    return $election;
}

//  SELECT COMMITTEE MEMBERS
# pulls list of committee members -> mysqli query object
function query_committee_members($conn, int $committee_id) {

    $members_sql = "SELECT * FROM `User` WHERE CNU_ID NOT IN(
        SELECT User_CNU_ID FROM `Committee Seat`
        WHERE Committee_Committee_ID=$committee_id)";
    $members = $conn->query($members_sql);
    return $members;
}

//  SELECT ARCHIVED COMMITTEE SEATS
# pulls archived committee seat info for given committee -> mysqli query object
function query_archived_committee_seats($conn, int $committee_id) {
    $committee_seats_sql = "SELECT * FROM `Committee Seat` WHERE Committee_Committee_ID = '$committee_id' AND Ending_Term IS NOT NULL;";
    $archived_seats = $conn->query($committee_seats_sql);
    return $archived_seats;
}