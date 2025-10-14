<?php
$filtro_studente = "";
$filtro_classe = "";
$filtro_materia = "";

if (isset($_GET['studente'])) {
    $filtro_studente = $_GET['studente'];
}
if (isset($_GET['classe'])) {
    $filtro_classe = $_GET['classe'];
}
if (isset($_GET['materia'])) {
    $filtro_materia = $_GET['materia'];
}
?>

<html>
<head>
    <title>Gestione Voti</title>
</head>
<body>
    <h2>Gestione Voti Studenti</h2>
    
    <form action="" method="get">
        Cognome Studente: <input type="text" name="studente">
        <br><br>
        Classe: <input type="text" name="classe">
        <br><br>
        Materia: <input type="text" name="materia">
        <br><br>
        <button type="submit">Cerca</button>
    </form>
    
    <hr>

<?php
$file = fopen("random-grades 1.csv", "r");

if (!$file) {
    echo "Errore apertura file";
} else {

    // Saltiamo la prima riga (intestazione)
    fgets($file);

    $contatore_totale = 0;
    $contatore_filtrati = 0;
    $somma_voti = 0;

    while (($riga = fgets($file)) !== false) {
        $campi = explode(",", $riga);

        $cognome = $campi[0];
        $nome = $campi[1];
        $classe = $campi[2];
        $materia = $campi[3];
        $data = $campi[4];
        $voto = $campi[5];
        $tipo = $campi[6];

        $contatore_totale = $contatore_totale + 1;

        $includi = 1;

        if ($filtro_studente != "") {
            if ($cognome != $filtro_studente) {
                $includi = 0;
            }
        }

        if ($filtro_classe != "") {
            if ($classe != $filtro_classe) {
                $includi = 0;
            }
        }

        if ($filtro_materia != "") {
            if ($materia != $filtro_materia) {
                $includi = 0;
            }
        }

        if ($includi == 1) {
            echo "Cognome: " . $cognome . " - ";
            echo "Nome: " . $nome . " - ";
            echo "Classe: " . $classe . " - ";
            echo "Materia: " . $materia . " - ";
            echo "Voto: " . $voto . " - ";
            echo "Tipo: " . $tipo;
            echo "<br>";

            $somma_voti = $somma_voti + $voto;
            $contatore_filtrati = $contatore_filtrati + 1;
        }
    }

    fclose($file);
    if ($contatore_filtrati > 0) {
        $media = $somma_voti / $contatore_filtrati;

        echo "<br>";
        echo "Media voti: " . $media . "<br>";
        echo "Numero voti: " . $contatore_filtrati;
    } else {
        echo "Nessun risultato trovato";
    }
}
?>

</body>
</html>
