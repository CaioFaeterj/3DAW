<?php
//dados de conexao com banco de dados do sistema
$host   = "localhost";
$user   = "root";
$pass   = "";
$db     = "produtos";

//captura acao que deve ser executada
$a = $_REQUEST["action"];

//identifica acao e invoca metodo a ser executado
switch ( $a ) {
    case "lista":
        carregarLista(); break;
    case "salvar":
        salvarForm(); break;
    case "excluir":
        excluirForm(); break;
    case "buscar":
        carregarProduto(); break;
}

//*****************************************************************************
// Metodo que carrega lista de clientes cadastrados
//
function carregarLista() {
    //abre conexao com banco de dados
    global $host, $user, $pass, $db;
    $mysqli = new mysqli( $host, $user, $pass, $db );
    if ( $mysqli->connect_errno ) { printf("Connect failed: %s\n", $mysqli->connect_error); exit(); }
    //preara e executa consulta de lista de produtos
    $sql = "SELECT * FROM produtos ORDER BY id DESC";
    if (!$res = $mysqli->query( $sql )) {
        echo "Erro ao executar SQL<br>";
        echo "Query: ".$sql."<br>";
        echo "Errno: ".$mysqli->errno."<br>";
        echo "Error: ".$mysqli->error."<br>";
        $res->close();
        exit;
    }
    //verifica se existe retorno de dados
    if ($res->num_rows === 0) {
        echo "Nenhum cadastro realizado até o momento.";
        $res->close();
        exit;
    }
    //monta tabela de resultados na pagina
    $saida = "<table>";
    while ($d = mysqli_fetch_array($res, MYSQLI_BOTH)) {
        $saida  = $saida. "<tr>"
                . "  <td style='width:25%'><img class=thumb src='/crud/imagens/".$d['foto']."' /></td>"
                . "  <td>"
                . "      <p class=plus>".$d['nome']."</p>"
                . "      <p>".$d['email']."</p>"
                . "      <p>".$d['telefone']."</p>"
                . "  </td>"
                . "  <td style='width:25%'><input type=button class=button value=Editar onClick='carregarCliente(\"".$d['id']."\");'></td>"
                . "  <td style='width:10%'><input type=button class='button delete' value=X onClick='excluirRegistro(\"".$d['id']."\");'></td>"
                . "</tr>";
    }
    $saida = $saida. "</table>";

    echo $saida;
    $res->close();
    $mysqli->close();
}

//*****************************************************************************
// Metodo que carrega dados do produto selecionado para alteracao
//
function carregarProduto() {
    //var_dump($_POST);
    if ( ! isset( $_POST ) || empty( $_POST ) ) {
        echo "Dados do formulário não chegaram no PHP.";
        exit;
    }
    //recupera ID a ser buscado
    if ( isset( $_POST["id"] ) && is_numeric( $_POST["id"] ) ) {
        $id = (int) $_POST["id"];

        //abre conexao com banco
        global $host, $user, $pass, $db;
        $mysqli = new mysqli( $host, $user, $pass, $db );
        if ( $mysqli->connect_errno ) { printf("Connect failed: %s\n", $mysqli->connect_error); exit(); }
        //prepara e executa sql para buscar produto
        $stmt = $mysqli->prepare("SELECT * FROM produto WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = &$row[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $parameters);
        while ($stmt->fetch()) {
            foreach($row as $key => $val) {
                $x[$key] = $val;
            }
            $results[] = $x;
        }
        //retorna array em formato JSON para leitura via ajax
        echo json_encode( $results );

        $mysqli->close();
    } else {
        echo "ID nao encontrado.";
    }
}

//*****************************************************************************
// Metodo que salva ou atualiza form de cadastro do cliente
//
function salvarForm() {
    //var_dump($_POST);
    if ( ! isset( $_POST ) || empty( $_POST ) ) {
        echo "Dados do formulário não chegaram no PHP.";
        exit;
    }
    //recupera dados do formulario html
    $id         = (int) $_POST["id"];
    $nome       = $_POST["nome"];
    $codigo     = $_POST["codigo"];
    $categoria  = $_POST["categoria"];
    $preco      = $_POST["preco"];
    $estoque    = $_POST["estoque"];
    $peso       = $_POST["peso"];
    $descricao  = $_POST["descricao"];
    $inclusao   = $_POST["inclusao"];
    $status     = $_POST["status"];
    $foto       = isset( $_FILES['foto'] ) ? $_FILES['foto'] : null;
    $nome_imagem= $_POST["nomeFoto"];
    //verifica dados do form
    $v = validarForm( $id, $nome, $codigo, $categoria, $preco, $estoque, $peso, $descricao, $inclusao, $status, $foto );
    if ($v != null) {
        echo "Problema encontrado:<br>".$v;
        exit;
    }
    //envia a imagem para o diretorio
    if (! empty( $foto ) ) {
        $imagem_tmp   = $foto['tmp_name'];
        $nome_imagem  = $foto['name']; //basename($foto['name']);
        $diretorio    = $_SERVER['DOCUMENT_ROOT'].'/crud/imagens/';
        $envia_imagem = $diretorio.$nome_imagem;

        if (! move_uploaded_file( $imagem_tmp, $envia_imagem ) ) {
            echo 'Erro ao enviar arquivo de imagem.';
            //echo "<br>Nome temporario do arquivo: ".$imagem_tmp."<br>Nome da Imagem: ".$nome_imagem."<br>Diretorio armazenamento: ".$diretorio."<br>envia: ".$envia_imagem;
            exit;
        }
    }
    //abre conexao com banco
    global $host, $user, $pass, $db;
    $mysqli = new mysqli( $host, $user, $pass, $db );
    if ( $mysqli->connect_errno ) { printf("Connect failed: %s\n", $mysqli->connect_error); exit(); }
    //prepara SQL para insert ou update dependendo do ID do form
    $sql = null;
    if ( $id > 1 ) {
        $sql = "UPDATE cliente SET nome=?, codigo=?, categoria=?, preco=?, estoque=?, peso=?, descricao=?, inclusao =?, status=?, foto =? WHERE id=".$id;
    } else {
        $sql = "INSERT INTO cliente nome, codigo, categoria, preco, estoque, peso, descricao, inclusao, status, foto  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    //prepara e executa sql para insert dos dados
    $stmt = $mysqli->prepare( $sql );
    $stmt->bind_param('ssis', $nome, $codigo, $categoria, $preco, $estoque, $peso, $descricao, $inclusao, $status, $nome_imagem); 
    $stmt->execute();
    //verifica se SQL de update foi executado
    if ( $id > 1 ) {
        if ( $stmt->affected_rows > 0 ) {
            echo "Produto atualizado com sucesso!";
        } else {
            echo "Não houve necessidade de atualizar os dados, nenhum valor foi modificado.";
        }
    //verifica se SQL de insert foi executado
    } else {
        if ( $stmt->affected_rows > 0 ) {
            echo "Produto cadastrado com sucesso!";
        } else {
            echo "Error: ".$stmt;
            exit;
        }
    }

    $mysqli->close();
}

//*****************************************************************************
// Metodo que exclui registro do produto
//
function excluirForm() {
    //var_dump($_POST);
    if ( ! isset( $_POST ) || empty( $_POST ) ) {
        echo "Dados do formulário não chegaram no PHP.";
        exit;
    }
    //recupera ID a ser deletado
    if ( isset( $_POST["id"] ) && is_numeric( $_POST["id"] ) ) {
        $id = (int) $_POST["id"];

        //abre conexao com banco
        global $host, $user, $pass, $db;
        $mysqli = new mysqli( $host, $user, $pass, $db );
        if ( $mysqli->connect_errno ) { printf("Connect failed: %s\n", $mysqli->connect_error); exit(); }
        //prepara e executa sql para delete do registro
        $stmt = $mysqli->prepare("DELETE FROM cliente WHERE id=?");
        $stmt->bind_param('i', $id); 
        $stmt->execute();
        //verifica se SQL foi executado com sucesso
        if ( $stmt->affected_rows > 0 ) {
            echo "Registro deletado com sucesso!";
        } else {
            echo "Error: ".$stmt;
            exit;
        }
        $mysqli->close();
    } else {
        echo "ID invalido para delete.";
    }
}

//*****************************************************************************
// Metodo que persiste dados do formulario em server-side
//
function validarForm( $id, $nome, $codigo, $categoria, $preco, $estoque, $peso, $descricao, $inclusao, $status, $foto ) {
    //validar campo nome
    if ( $nome == null || trim( $nome ) == "" ) {
        return "Campo Nome deve ser preenchido.";
    }
    //validar campo codigo
    if ( $codigo == null || trim( $codigo ) == "" ) {
        return "Campo codigo deve ser preenchido.";
    }

    return null;
}
