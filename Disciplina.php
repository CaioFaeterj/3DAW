<?php
/**
 *
 * CAIO FELIPE BENTO PEREIRA
 */
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_disciplina = (isset($_POST["id_disciplina"]) && $_POST["id_disciplina"] != null) ? $_POST["id_disciplina"] : "";
    $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
    $periodo = (isset($_POST["periodo"]) && $_POST["periodo"] != null) ? $_POST["periodo"] : "";
    $id_requisito = (isset($_POST["id_requisito"]) && $_POST["id_requisito"] != null) ? $_POST["id_requisito"] : NULL;
    $creditos = (isset($_POST["creditos"]) && $_POST["creditos"] != null) ? $_POST["creditos"] : NULL;
} else if (!isset($id_disciplina)) {
    // Se não se não foi setado nenhum valor para variável $id_disciplina
    $id_disciplina = (isset($_GET["id_disciplina"]) && $_GET["id_disciplina"] != null) ? $_GET["id_disciplina"] : "";
    $nome = NULL;
    $periodo = NULL;
    $id_requisito = NULL;
    $creditos = NULL;
}
 
// Cria a conexão com o banco de dados
try {
    $conexao = new PDO("mysql:host=localhost;dbname=disciplinas", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($id_disciplina != "") {
            $stmt = $conexao->prepare("UPDATE disciplina SET nome=?, periodo=?, id_requisito=?, creditos=? WHERE id_disciplina = ?");
            $stmt->bindParam(5, $id_disciplina);
        } else {
            $stmt = $conexao->prepare("INSERT INTO disciplina (nome, periodo, id_requisito, creditos) VALUES (?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $periodo);
        $stmt->bindParam(3, $id_requisito);
        $stmt->bindParam(4, $creditos);
 
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id_disciplina = null;
                $nome = null;
                $periodo = null;
                $id_requisito = null;
                $creditos = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id_disciplina != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM disciplina WHERE id_disciplina = ?");
        $stmt->bindParam(1, $id_disciplina, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id_disciplina = $rs->id_disciplina;
            $nome = $rs->nome;
            $periodo = $rs->periodo;
            $id_requisito = $rs->id_requisito;
            $creditos = $rs->creditos;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id_disciplina != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM disciplina WHERE id_disciplina = ?");
        $stmt->bindParam(1, $id_disciplina, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id_disciplina = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>DISCIPLINAS</title>
        </head>
        <body>
            <form action="?act=save" method="POST" name="form1" >
                <h1>CONTROLE DE DISCIPLINAS</h1>
                <hr>
                <input type="hidden" name="id_disciplina" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id_disciplina) && $id_disciplina != null || $id_disciplina != "") {
                    echo "value=\"{$id_disciplina}\"";
                }
                ?> />
                Nome:
               <input type="text" name="nome" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($nome) && $nome != null || $nome != "") {
                   echo "value=\"{$nome}\"";
               }
               ?> />
               Periodo:
               <input type="text" name="periodo" <?php
 
               // Preenche o periodo no campo periodo com um valor "value"
               if (isset($periodo) && $periodo != null || $periodo != "") {
                   echo "value=\"{$periodo}\"";
               }
               ?> />
               id_requisito:
               <input type="text" name="id_requisito" <?php
 
               // Preenche o id_requisito no campo celular com um valor "value"
               if (isset($id_requisito) && $id_requisito != null || $id_requisito != "") {
                   echo "value=\"{$id_requisito}\"";
               }
               ?> />
               creditos:
               <input type="text" name="creditos" <?php
 
               // Preenche o id_requisito no campo celular com um valor "value"
               if (isset($creditos) && $creditos != null || $creditos != "") {
                   echo "value=\"{$creditos}\"";
               }
               ?> />
               <input type="submit" value="salvar" />
               <input type="reset" value="Novo" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>Nome</th>
                    <th>Periodo</th>
                    <th>id_requisito</th>
                    <th>creditos</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $conexao->prepare("SELECT * FROM disciplina");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->nome."</td><td>".$rs->periodo."</td><td>".$rs->id_requisito."</td><td>".$rs->creditos
                                       ."</td><td><center><a href=\"?act=upd&id_disciplina=".$rs->id_disciplina."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp"
                                       ."<a href=\"?act=del&id_disciplina=".$rs->id_disciplina."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Erro: Não foi possível recuperar os dados do banco de dados";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
        </body>
    </html>