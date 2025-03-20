<?php
session_start();

if (isset($_POST['staffID'])) {
    $_SESSION['staffID']   = $_POST['staffID'];
}

if (isset($_POST['delegateID'])) {
    $_SESSION['delegateID']   = $_POST['delegateID'];
}

if (isset($_POST['aDate'])) {
        $_SESSION['aDate']   = $_POST['aDate'];
}
    
if (isset($_POST['aTime'])) {
        $_SESSION['aTime']   = $_POST['aTime'];
}
    
if (isset($_POST['pID'])) {
            $_SESSION['pID']   = $_POST['pID'];
}

$staffID   = isset($_SESSION['staffID']) ? $_SESSION['staffID'] : '';
$delegateID   = isset($_SESSION['delegateID']) ? $_SESSION['delegateID'] : '';
$aDate   = isset($_SESSION['aDate']) ? $_SESSION['aDate'] : '';
$aTime   = isset($_SESSION['aTime']) ? $_SESSION['aTime'] : '';
$pID   = isset($_SESSION['pID']) ? $_SESSION['pID'] : '';

function connect() {
    $host = 'dragon.ukc.ac.uk';
    $dbname = 'sk993';
    $user = 'sk993';
    $pwd = 'uediso9';
    
try {
    
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        } 
    
        catch (PDOException $e) {
            echo "PDOException: ".$e->getMessage();
        }
}
 
    $conn = connect();

try {
        $sql = "
            UPDATE Appointment
    SET Staff_sID = :delegateID
    WHERE aDate = :aDate
      AND aTime = :aTime
      AND Patient_pID = :pID
      AND Staff_sID = :staffID
        ";

$handle = $conn->prepare($sql);
$handle->execute([
    ':delegateID' => $delegateID,
    ':aDate'      => $aDate,
    ':aTime'      => $aTime,
    ':pID'        => $pID,
    ':staffID'    => $staffID,
]);

        
} catch (PDOException $e) {
        echo "PDOException: ".$e->getMessage();
        }
        
$conn = null;

header("Location: onestaff.php");
exit();

?>