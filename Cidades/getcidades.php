<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $servidor = "localhost";
    $usuario = "root";
    $senha = "";
    $nomeBanco = "faeterj3dawNoite";
    $conn = new mysqli($servidor, $usuario, $senha, $nomeBanco);

    $cidadesRio = array("angra", "Rio de janeiro", "Petropolis");
    $estado = $_GET["estado"];

    // passar o comando sql para ler a tabela
    $query = "SELECT * FROM estado where uf = '$estado'";
    $result = $conn->query($query);
    $linha = $result->fetch_assoc();
    $idEstado = $linha["id"];

    $query2 = "SELECT * FROM cidade where estado = $idEstado";
    $result2 = $conn->query($query2);
    $linha2 = $result2->fetch_assoc();
    $nomeCidade = $linha2["nome"];
    $jcidades = json_encode($result);
    $arrCidades[] = array();
    $newArr = array();
    $i=0;
    $strResponse = "";
    $strResponse2 = "";
    while ( $db_field = $result2->fetch_assoc()) {
        $aa = strtr(utf8_decode($db_field["nome"]), utf8_decode(' àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
            '_aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
        $txtNome = str_replace( array(' ', 'à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô'
        ,'õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó'
        ,'Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'),
            array('_', 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u'
            ,'u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U'
            ,'U','U', 'Y'), $db_field["nome"]);

        $newArr[] = $db_field["nome"];
        $arrCidades[$i] = $aa;      //json_encode($aa);     //$db_field["nome"];
        if ($db_field["nome"] != "") {
            $strResponse .= json_encode($db_field["nome"]) . "$i,";
            $strResponse2 .= json_encode($aa) . ", ";
        }
        $i++;
        //print(json_encode($db_field));
    }
    //echo $nomeCidade;
    //print($strResponse2);
    //echo json_encode($newArr);
    echo json_encode($arrCidades);      //."\n";
    //echo $arrCidades;
    //echo $strResponse2;
}

?>
