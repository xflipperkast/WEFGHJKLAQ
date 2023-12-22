<?php
require_once 'classes/Wishes.php';

$wishes = new Wishes();
$result = $wishes->readall(); // Pas dit aan op basis van hoe je 'read' methode werkt
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wensenlijst</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .wish-item {
            background: #fff;
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .wish-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Wensenlijst</h1>
        <?php
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='wish-item'>";
            echo "<p>Wens: " . $row['wish'] . "</p>";
            // Voeg hier eventueel extra wensinformatie toe
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
