<?php
session_start();

if (isset($_POST['staffName'])) {
$_SESSION['staffName'] = $_POST['staffName'];
}

if (isset($_POST['staffID'])) {
$_SESSION['staffID']   = $_POST['staffID'];
}

$staffName = isset($_SESSION['staffName']) ? $_SESSION['staffName'] : ' ';
$staffID   = isset($_SESSION['staffID']) ? $_SESSION['staffID'] : ' ';

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
        SELECT 
        *
        FROM Appointment
        LEFT JOIN Patient on Patient.pID = Appointment.Patient_pID
        LEFT JOIN Staff on Staff.sID = Appointment.Staff_sID
        WHERE Staff.sID =  $staffID 
        ";

$handle = $conn->prepare($sql);
$handle->execute();

$res = $handle->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "PDOException: ".$e->getMessage();
        }

$conn = null;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Healthcare System - Staff Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <h3>Doctor Information:</h3>
    <p>Displaying data for <?php echo $staffName; ?> (ID = <?php echo $staffID; ?>)</p>
    <button onclick="location.href='index.php'">Exit</button>
</p>
            <h3>Appointments</h3>
            <table>
            <tr>
                <th> Date</th>
                <th> Time </th> 
                <th> Patient </th>
                <th> Action</th>
            </tr>
            <?php foreach ($res as $row) { ?>
            <tr>
                <td> <?php echo ($row['aDate']); ?> </td>
                <td> <?php echo ($row['aTime']); ?> </td>
                <td> <?php echo ($row['pName']); ?> 
                (ID = <?php echo $row['pID']; ?>) </td>
                <td>
                    <form action="onemove.php" method="post">
                    <input type="hidden" name="aDate" value="<?php echo $row['aDate']; ?>">
                    <input type="hidden" name="aTime" value="<?php echo $row['aTime']; ?>">
                    <input type="hidden" name="pID" value="<?php echo $row['pID']; ?>">
                    <input type="hidden" name="staffID" value="<?php echo $staffID; ?>">
                    <input type="hidden" name="staffName" value="<?php echo $staffName; ?>">
                        <button type="submit">Move</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
            </table>
    </body>
</html>
