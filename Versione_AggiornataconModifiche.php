<?php
$studente_filtro = "";
$classe_filtro = "";
$materia_filtro = "";
$raggruppa = "";

if (isset($_GET['studente'])) 
{
    $studente_filtro = $_GET['studente'];
}
if (isset($_GET['classe'])) 
{
    $classe_filtro = $_GET['classe'];
}
if (isset($_GET['materia'])) 
{
    $materia_filtro = $_GET['materia'];
}
if (isset($_GET['raggruppa'])) 
{
    $raggruppa = $_GET['raggruppa'];
}

$file = "random-grades 1.csv";

if (isset($_POST['add'])) 
{
    $riga = $_POST['cognome'].",".
            $_POST['nome'].",".
            $_POST['classe'].",".
            $_POST['materia'].",".
            $_POST['data'].",".
            $_POST['voto'].",".
            $_POST['tipo']."\n";

    $f = fopen($file,"a");
    fwrite($f,$riga);
    fclose($f);
}

if (isset($_GET['del'])) 
{
    $righe = file($file);
    $f = fopen($file,"w");

    for ($i = 0; $i < count($righe); $i++) 
    {
        if ($i != $_GET['del']) 
        {
            fwrite($f,$righe[$i]);
        }
    }
    fclose($f);
}

if (isset($_POST['mod'])) 
{
    $righe = file($file);
    $i = $_POST['i'];
    $righe[$i] = $_POST['cognome'].",".
                  $_POST['nome'].",".
                  $_POST['classe'].",".
                  $_POST['materia'].",".
                  $_POST['data'].",".
                  $_POST['voto'].",".
                  $_POST['tipo']."\n";
    $f = fopen($file,"w");
    for ($j = 0; $j < count($righe); $j++) 
    {
        fwrite($f,$righe[$j]);
    }
    fclose($f);
}

$righe = file($file);
$voti = array();
$materie_list = array();

for ($i = 1; $i < count($righe); $i++) 
{
    $c = explode(",",$righe[$i]);
    if (count($c) >= 7) 
    {
        $includi = 1;
        if ($studente_filtro != "") { if ($c[0] != $studente_filtro) { $includi = 0; } }
        if ($classe_filtro != "") { if ($c[2] != $classe_filtro) { $includi = 0; } }
        if ($materia_filtro != "") { if ($c[3] != $materia_filtro) { $includi = 0; } }

        if ($includi == 1) 
        {
            $voti[] = $c;
            $trovato = 0;
            for ($j = 0; $j < count($materie_list); $j++) 
            {
                if ($materie_list[$j] == $c[3]) { $trovato = 1; break; }
            }
            if ($trovato == 0) { $materie_list[] = $c[3]; }
        }
    }
}
?>

<html>
<body>
<h3>Gestione Voti Studenti</h3>

<form>
Studente: <input name="studente" value="<?php echo $studente_filtro; ?>">
Classe: <input name="classe" value="<?php echo $classe_filtro; ?>">
Materia: <input name="materia" value="<?php echo $materia_filtro; ?>"><br>
Raggruppa:
<select name="raggruppa">
<option value="">No</option>
<option value="studente" <?php if($raggruppa=="studente") echo "selected";?>>Studente</option>
<option value="classe" <?php if($raggruppa=="classe") echo "selected";?>>Classe</option>
</select>
<button type="submit">Cerca</button>
</form>

<hr>

<form method="post">
<input type="hidden" name="add" value="1">
Cognome: <input name="cognome">
Nome: <input name="nome">
Classe: <input name="classe">
Materia: <input name="materia">
Data: <input name="data">
Voto: <input name="voto">
Tipo: <input name="tipo">
<button>Aggiungi</button>
</form>

<hr>

<?php
if ($raggruppa == "studente") 
{
    for ($i = 0; $i < count($voti); $i++) 
    {
        $studente = $voti[$i][0]." ".$voti[$i][1];
        $somma = 0;
        $conta = 0;
        for ($j = 0; $j < count($voti); $j++) 
        {
            if ($voti[$j][0] == $voti[$i][0]) 
            {
                $somma += $voti[$j][5];
                $conta++;
            }
        }
        if ($conta > 0) 
        {
            $media = $somma/$conta;
            echo "<b>".$studente."</b> media: ".$media."<br>";
        }
    }
}

if ($raggruppa == "classe") 
{
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Studente</th>";
    for ($m = 0; $m < count($materie_list); $m++) 
    {
        echo "<th>".$materie_list[$m]."</th>";
    }
    echo "</tr>";

    for ($i = 0; $i < count($voti); $i++) 
    {
        $studente = $voti[$i][0]." ".$voti[$i][1];
        echo "<tr>";
        echo "<td>".$studente."</td>";

        for ($m = 0; $m < count($materie_list); $m++) 
        {
            $somma = 0;
            $conta = 0;
            for ($j = 0; $j < count($voti); $j++) 
            {
                if ($voti[$j][0] == $voti[$i][0] && $voti[$j][3] == $materie_list[$m]) 
                {
                    $somma += $voti[$j][5];
                    $conta++;
                }
            }
            if ($conta > 0) 
            {
                echo "<td>".($somma/$conta)."</td>";
            } 
            else 
            {
                echo "<td>-</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
}

if ($raggruppa == "") 
{
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>";
    echo "<th>Cognome</th><th>Nome</th><th>Classe</th><th>Materia</th><th>Data</th><th>Voto</th><th>Tipo</th><th>Azioni</th>";
    echo "</tr>";

    for ($i = 1; $i < count($righe); $i++) 
    {
        $c = explode(",", $righe[$i]);
        if (count($c) >= 7) 
        {
            echo "<tr>";
            echo "<td>".$c[0]."</td>";
            echo "<td>".$c[1]."</td>";
            echo "<td>".$c[2]."</td>";
            echo "<td>".$c[3]."</td>";
            echo "<td>".$c[4]."</td>";
            echo "<td>".$c[5]."</td>";
            echo "<td>".$c[6]."</td>";
            echo "<td><a href='?del=".$i."'>Elimina</a> | <a href='?mod=".$i."'>Modifica</a></td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

if (isset($_GET['mod'])) 
{
    $i = $_GET['mod'];
    $c = explode(",", $righe[$i]);
    echo "<hr>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='mod' value='1'>";
    echo "<input type='hidden' name='i' value='".$i."'><br>";
    echo "Cognome: <input name='cognome' value='".$c[0]."'> ";
    echo "Nome: <input name='nome' value='".$c[1]."'> ";
    echo "Classe: <input name='classe' value='".$c[2]."'> ";
    echo "Materia: <input name='materia' value='".$c[3]."'><br>";
    echo "Data: <input name='data' value='".$c[4]."'> ";
    echo "Voto: <input name='voto' value='".$c[5]."'> ";
    echo "Tipo: <input name='tipo' value='".$c[6]."'> ";
    echo "<button>Salva</button>";
    echo "</form>";
}
?>
</body>
</html>
