<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

require_once 'classes/Wishes.php';

$wishes = new Wishes();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $wishes->user_id = $user_id;
                $wishes->wish = $_POST['wish'];
                $wishes->create();

                // Redirect to the same page using GET to prevent form resubmission
                header("Location: dashboard.php");
                exit();
                break;
            case 'update':
                    $wishes->id = $_POST['id'];
                    $wishes->wish = $_POST['wish'];
                    $updatedWish = $wishes->update();
                    if ($updatedWish) {
                        $response = array('success' => true, 'newWish' => $updatedWish);
                    } else {
                        $response = array('success' => false, 'error' => 'Failed to update wish.');
                    }
                    header('Content-Type: application/json'); // Set JSON content type
                    echo json_encode($response);
                    exit();
                    break;                
            case 'delete':
                $wishes->id = $_POST['id'];
                $deleted = $wishes->delete();
                if ($deleted) {
                    // For successful deletions
                    $response = array('success' => true);
                } else {
                    // For errors
                    $response = array('success' => false, 'error' => 'Failed to delete wish.');
                }
                echo json_encode($response);
                exit(); // Important to exit after sending JSON response
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
<style>
#tabs ul {
    list-style-type: none;
    padding: 0;
}

#tabs ul li {
    display: inline;
    margin-right: 10px;
}

#tabs ul li a {
    text-decoration: none;
    padding: 5px 10px;
    background-color: #337ab7;
    color: #fff;
    border-radius: 5px;
}

/* Style the table */
#wishes-table {
    width: 100%;
    border-collapse: collapse;
}

#wishes-table th, #wishes-table td {
    padding: 8px;
    border: 1px solid #ddd;
}

#wishes-table th {
    background-color: #f2f2f2;
    text-align: left;
}

/* Style the update and delete buttons */
button {
    padding: 5px 10px;
    background-color: #5bc0de;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color: #357ebd;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="tabs">
        <ul>
            <li><a href="#tab-add">Toevoegen</a></li>
            <li><a href="#tab-view">Bekijken</a></li>
        </ul>
        <div id="tab-add">
            <form id="add-wish-form" method="post" action="dashboard.php">
                <input type="hidden" name="action" value="add">
                <label for="wish">Wens:</label>
                <input type="text" id="wish" name="wish">
                <input type="submit" value="Toevoegen">
            </form>
        </div>

        <div id="tab-view">
    <table id="wishes-table">
        <thead>
            <tr>
                <th>Wens</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $result = $wishes->read($user_id);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td><input type='text' id='wish-" . $row['id'] . "' value='" . $row['wish'] . "' /></td>";
                echo "<td><button onclick='updateWish(" . $row['id'] . ")'>Bewerken</button>";
                echo "<button onclick='deleteWish(" . $row['id'] . ")'>Verwijderen</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    </div>

    <script>
    $(document).ready(function() {
        $("#tabs div").hide();
        $("#tabs div:first").show();
        $("#tabs ul li a").click(function() {
            $("#tabs div").hide();
            var activeTab = $(this).attr("href");
            $(activeTab).show();

            return false;
        });
    });


    function updateWish(wishId) {
        var updatedWish = $('#wish-' + wishId).val();
        $.ajax({
            url: 'dashboard.php',
            type: 'POST',
            data: { action: 'update', id: wishId, wish: updatedWish },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Success: Update the input field with the new wish
                        $('#wish-' + wishId).val(data.newWish);
                        // Provide feedback to the user
                        alert("Wish updated successfully.");
                    } else {
                        // Error: Handle error and provide appropriate feedback to the user
                        alert("Failed to update wish. Please try again.");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response: " + e);
                }
            }
        });
    }



    function deleteWish(wishId) {
        $.ajax({
            url: 'dashboard.php',
            type: 'POST',
            data: { action: 'delete', id: wishId },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Success: Remove the wish row and reload the page
                    $('#row-' + wishId).remove();
                    // Provide feedback to the user
                    alert("Wish deleted successfully.");
                    location.reload(); // Reload the page
                } else {
                    // Error: Handle error and provide appropriate feedback to the user
                    alert("Failed to delete wish. Please try again.");
                }
            }
        });
    }

</script>
</body>
</html>
