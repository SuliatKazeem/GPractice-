<?php
session_start();

if (isset($_POST['staffName'])) {
$_SESSION['staffName'] = $_POST['staffName'];
}

if (isset($_POST['staffID'])) {
$_SESSION['staffID']   = $_POST['staffID'];
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
$staffName = isset($_SESSION['staffName']) ? $_SESSION['staffName'] : '' ;
$staffID   = isset($_SESSION['staffID']) ? $_SESSION['staffID'] : '';
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
        SELECT *
        FROM Staff
        WHERE sID !=  $staffID
        AND sID NOT IN
        (SELECT Staff_sID FROM Appointment
        WHERE aDate = '$aDate' AND aTime = '$aTime' ) "
;

$handle = $conn->prepare($sql);
$handle->execute();


$delegates = $handle->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "PDOException: ".$e->getMessage();
        }

$conn = null;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Healthcare System</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <p>Alternatives for appointment on 2024-02-09 at 11:10 by sID = <?php echo $staffID; ?>
        <button onclick="location.href='index.php'"> Exit </button>
        </p>
            <h3>Alternative staff: </h3>
            <form action="onedelegate.php" method="post">
            <input type="hidden" name="staffID" value="<?php echo $staffID; ?>">
            <input type="hidden" name="staffName" value="<?php echo $staffName; ?>">
            <input type="hidden" name="aDate" value="<?php echo $aDate; ?>">
            <input type="hidden" name="aTime" value="<?php echo $aTime; ?>">
            <input type="hidden" name="pID" value="<?php echo $pID; ?>">
                <table>
                <tr>
                <th> Name</th>
                <th> Type</th> 
                <th> sID </th>
                <th> 
                    <button type="submit">Delegate</button>
                </th>
            </tr>
            <?php foreach ($delegates as $row) { ?>
            <tr>
                <td> <?php echo ($row['sName']); ?> </td>
                <td> <?php echo ($row['sType']); ?> </td>
                <td> <?php echo ($row['sID']); ?> </td>
                <td>
                    <input type="radio"name="substituteID" value="<?php echo $row['sID']; ?>">
                </td>
            </tr>
            <?php } ?>
            </table>
            </form>
    </body>
</html>
