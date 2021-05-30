<?php  $this->load->view('comuns/header'); ?>
<?php  $this->load->view('comuns/menu'); ?>
<body>

    <div class="container-fluid">
        <h1> EPISÓDIOS DE <?php echo $querys[0]->Titulo; ?> </h1>
        <form action="<?= base_url('episodio/gerirEps')?>" method="post" enctype="multipart/form-data">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">TITULO</th>
                        <th scope="col">URL</th>
                        <th scope="col">DATA RELEASE</th>
                        <th scope="col">ACTION</th>
                    </tr>
                </thead>
                <?php foreach ($querye as $episodio): ?>
                <tbody>
                    <tr>
                        <td><?php echo $episodio['idEpisodio'] ?></td>
                        <td><?php echo $episodio['titulo'] ?></td>
                        <td><?php echo $episodio['url'] ?></td>
                        <td><?php echo $episodio['dataRelease'] ?></td>
                        <td>
                            <button class="btn btn-outline-dark btn-sm" type="submit" value="<?php echo $episodio['idEpisodio'] ?>" name="Editar"> Editar </button>
                            <button class="btn btn-outline-dark btn-sm" type="submit" value="<?php echo $episodio['idEpisodio'] ?>" name="Remover"> Remover </button>
                        </td>
                    </tr>

                </tbody>
                <?php endforeach; ?>
            </table>
        </form>
    </div>


<?php $this->load->view('comuns/footer'); ?>