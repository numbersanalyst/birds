<html lang="pl">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Panel admina</title>
</head>


<body>
    <?php


    $conn = mysqli_connect("localhost", "root", "", "ptaki");


    if (mysqli_connect_errno()) {
        echo "Połączenie nie zostało nawiązane: " . mysqli_connect_error();
        exit();
    }


    ?>
    <header>
        <h1>Zbiór wiedzy o ptakach</h1>
    </header>
    <main>
        <div>
            <h2>Gatunki ptaków:</h2>
            <ol>
                <?php
                $SQL = "SELECT nazwa_zwyczajowa FROM gatunki;";
                $result = mysqli_query($conn, $SQL);


                while ($row = mysqli_fetch_array($result)) {
                    echo "<li>" . $row["nazwa_zwyczajowa"] . "</li>";
                }
                ?>
            </ol>
        </div>
        <div>
            <form method="POST" action="index.php">
                <h2> Konkretne informacje o wybranym ptaku</h2>
                <div>
                    <label>Numer gatunku&nbsp;<input type="number" name="id"></label>
                    <input type="submit">
                    <?php
                    if (isset($_POST['id'])) {
                        $selected_nr = $_POST['id'];


                        $SQL = "SELECT id_gatunku, nazwa_zwyczajowa, nazwa_lacinska FROM gatunki WHERE id_gatunku = $selected_nr;";
                        $result = mysqli_query($conn, $SQL);

                        while ($row = mysqli_fetch_array($result)) {
                            echo "<p>Nazwa zwyczajowa: <strong>" . $row["nazwa_zwyczajowa"] . "</strong></p>";
                            echo "<p>Nazwa łacińska: <strong>" . $row["nazwa_lacinska"] . "</strong></p>";
                        }


                        $SQL = "SELECT * FROM obserwacje WHERE id_gatunku = $selected_nr";


                        $result = mysqli_query($conn, $SQL);
                        echo "<table>";
                        echo "<tr></tr>";
                        echo "<tr><th colspan=5>Wyniki z obserwacji</th></tr>";
                        echo "<tr><th>lokalizacja</th><th>widoczny od</th><th>widoczny do</th><th>liczebność</th><th>zachowanie</th></tr>";
                        while ($row = mysqli_fetch_array($result)) {
                            $data_od = date("m/d/Y H:i", strtotime($row[2]));
                            $data_do = date("m/d/Y H:i", strtotime($row[3]));


                            $id_lokalizacji = $row['ID_lokalizacji'];
                            $sec_result = mysqli_query($conn, "SELECT lokalizacja FROM lokalizacje WHERE id_lokalizacji = $id_lokalizacji;");
                            $lokalizacja = mysqli_fetch_column($sec_result, 0);

                            echo "<tr><td>$lokalizacja</td><td>$data_od</td><td>$data_do</td><td>$row[4]</td><td>$row[5]</td></tr>";
                        }
                        echo "</table>";
                    }
                    ?>
                </div>
            </form>
        </div>
    </main>
    <?php
    exit()
    ?>
</body>


</html>