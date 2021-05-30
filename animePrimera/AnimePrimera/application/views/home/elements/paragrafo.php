<?php  $this->load->view('comuns/header'); ?>
<?php  $this->load->view('comuns/menu'); ?>
<body>

<div id="container">
    <h1>Criar Elemento</h1>
</div>
<form action="<?= base_url('homepage/criarP')?>" method="post" enctype="multipart/form-data">
    <div id="container">

        <h4>Parágrafo</h4>
        <div class="form-group">
            <label> Conteudo:
                <input name="conteudo" type="text">
            </label>
        </div>

        <h4>Título</h4>
        <div class="form-group">
            <label>
                <input type="checkbox" name="tituloEx" value="1"> Adicionar Título
            </label>
        </div>
        <div class="form-group">
            <label> (Em caso de Adicionar Título)Título:
                <input name="titulo" type="text">
            </label>
        </div>

        <h4>Posicionamento</h4>
        <div class="form-group">
            <label> Posicão:
                <input name="posicao" type="number">
            </label>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" class="label-info" name="perContainer" value="1"> Linha Inteira
            </label>
        </div>
        <div class="form-group">
            <label> Lado onde se Posicionará(caso Linha Inteira não seja escolhida)
                <select name="side">
                    <option value="1">Esquerda</option>
                    <option value="2">Direita</option>
                </select>
            </label>
        </div>

        <h4>Extras</h4>
        <div class="form-group">
            <label>
                <input type="checkbox" name="botaoEx" value="1"> Adicionar Botão
            </label>
        </div>

        <div class="form-group">
            <input class="form-control" type="submit" class="btn btn-primary" value="Criar">
        </div>
    </div>
</form>
<?php $this->load->view('comuns/footer'); ?>