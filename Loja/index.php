<?php

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Loja Ximbolé Bahiano</title>
    <link rel="stylesheet" type="text/css" href="styles.css" async>
    <script src="scripts.js"></script>
</head>

<body>
<div id="conteudo">
    <h2>Loja Ximbolé Bahiano</h2>
    <div id="msg-php" class="no-display"></div>

    <form method="POST" enctype="multipart/form-data" onSubmit="salvarForm(); return false;" id="frmCrud">
    <fieldset>
        <legend>Nome:</legend>
        <input id="nome" type=text class=input-text required placeholder="Digite o nome do produto" size=20 name=nome onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Codigo de barras:</legend>
        <input id="codigo" type=text class=input-text required placeholder="Informe o codigo de barras" size=30 name=codigo onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Categoria:</legend>
        <input id="categoria" type=text class=input-text required pattern="\d*" placeholder="Informe a categoria" size=50 name=categoria onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Preço:</legend>
        <input id="preco" type=text class=input-text required placeholder="preco do produto" size=30 name=preco onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Estoque:</legend>
        <input id="estoque" type=text class=input-text required placeholder="Informe o estoque" size=30 name=estoque onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Peso:</legend>
        <input id="peso" type=text class=input-text required placeholder="Informe o peso do produto em gramas" size=30 name=peso onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Descrição:</legend>
        <input id="descricao" type=text class=input-text required placeholder="Informe a descrição" size=30 name=descricao onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Data de inclusão:</legend>
        <input id="inclusao" type=text class=input-text required placeholder="Informe a data de inclusao" size=30 name=inclusao onFocus="inputOn(this)" onBlur="inputOff(this)"/>
        <legend>Status:</legend>
        <input id="status" type=text class=input-text required placeholder="Informe se é um produto ativo" size=30 name=status onFocus="inputOn(this)" onBlur="inputOff(this)"/>
    </fieldset>
    <fieldset>
        <legend>Foto:</legend>
        <input type=file id="foto" name=foto class=input-text accept="image/png, image/jpeg"/>
        <img id="image" class=thumb />
    </fieldset>
    <input id="id" type=hidden value="-1" />
    <input id="nomeFoto" type=hidden value="" />
    <input type=reset class=button id="btnLimpar" value="Limpar" />
    <input type=submit class=button id="btnSalvar" value="Salvar" />
    </form>
</div>

<div id="lista">
    <script type="text/javascript">carregarLista();</script>
</div>
</body>
</html>