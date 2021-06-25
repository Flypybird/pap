<?php  $this->load->view('comuns/header'); ?>
<?php  $this->load->view('comuns/menu'); ?>
<body>

<div class="container-fluid">
    <div class="row mypfpbg">
        <div class="col">
            <img class="myprofilepfp text-center" src="<?php echo base_url('./resources/img/pfp/' . $fotoPerfil) ?>" title="userPFP" alt="userPFP"/>
            <h3 class="mypusername"><b>Username: </b><small><?php echo $username ?></small></h3>
            <h3 class="mypemail"><b>Email:</b><small> <?php echo $email ?></small></h3>
            <a class="btn-block text-center mypedit" href="<?php echo base_url('user/editUser/' . $idUser) ?>">Editar Perfil</a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3 id="centered" class="text-center"> Meus Favoritos </h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="r" class="card-group">
                <?php foreach ($serieFav as $serie): ?>
                    <a href="<?php echo base_url('/serie/seriesinfo/' . $serie['idSerie']) ?>">
                        <div class="card text-white bg-dark mb-3">
                            <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/' . $serie['Photo']) ?>" alt="Thumbnail">
                            <div id="middle" class="card-body">
                                <h5 id="text" class="card-title"><?php echo $serie['Titulo'] ?></h5>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?php echo base_url('user/allSeriesFav/' . $idUser)?>">
                    <div class="card text-white bg-dark mb-3">
                        <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/+.png')?>" alt="Thumbnail">
                        <div id="middle" class="card-body">
                            <h5 id="text" class="card-title">+ Series</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3 id="centered" class="text-center"> Séries que Segues </h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="r" class="card-group">
                <?php foreach ($serieSeg as $serie): ?>
                    <a href="<?php echo base_url('/serie/seriesinfo/' . $serie['idSerie']) ?>">
                        <div class="card text-white bg-dark mb-3">
                            <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/' . $serie['Photo']) ?>" alt="Thumbnail">
                            <div id="middle" class="card-body">
                                <h5 id="text" class="card-title"><?php echo $serie['Titulo'] ?></h5>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?php echo base_url('user/allSeriesSeg/' . $idUser)?>">
                    <div class="card text-white bg-dark mb-3">
                        <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/+.png')?>" alt="Thumbnail">
                        <div id="middle" class="card-body">
                            <h5 id="text" class="card-title">+ Series</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h3 id="centered" class="text-center"> Séries que estás a ver </h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="r" class="card-group">
                <?php foreach ($serieAss as $serie): ?>
                    <a href="<?php echo base_url('/serie/seriesinfo/' . $serie['idSerie']) ?>">
                        <div class="card text-white bg-dark mb-3">
                            <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/' . $serie['Photo']) ?>" alt="Thumbnail">
                            <div id="middle" class="card-body">
                                <h5 id="text" class="card-title"><?php echo $serie['Titulo'] ?></h5>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?php echo base_url('user/allSeriesAss/' . $idUser)?>">
                    <div class="card text-white bg-dark mb-3">
                        <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/+.png')?>" alt="Thumbnail">
                        <div id="middle" class="card-body">
                            <h5 id="text" class="card-title">+ Series</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h3 id="centered" class="text-center"> Séries em Espera </h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="r" class="card-group">
                <?php foreach ($serieHol as $serie): ?>
                    <a href="<?php echo base_url('/serie/seriesinfo/' . $serie['idSerie']) ?>">
                        <div class="card text-white bg-dark mb-3">
                            <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/' . $serie['Photo']) ?>" alt="Thumbnail">
                            <div id="middle" class="card-body">
                                <h5 id="text" class="card-title"><?php echo $serie['Titulo'] ?></h5>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?php echo base_url('user/allSeriesHol/' . $idUser)?>">
                    <div class="card text-white bg-dark mb-3">
                        <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/+.png')?>" alt="Thumbnail">
                        <div id="middle" class="card-body">
                            <h5 id="text" class="card-title">+ Series</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <h3 id="centered" class="text-center"> Séries Dropadas </h3>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="r" class="card-group">
                <?php foreach ($serieDro as $serie): ?>
                    <a href="<?php echo base_url('/serie/seriesinfo/' . $serie['idSerie']) ?>">
                        <div class="card text-white bg-dark mb-3">
                            <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/' . $serie['Photo']) ?>" alt="Thumbnail">
                            <div id="middle" class="card-body">
                                <h5 id="text" class="card-title"><?php echo $serie['Titulo'] ?></h5>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
                <a href="<?php echo base_url('user/allSeriesDro/' . $idUser)?>">
                    <div class="card text-white bg-dark mb-3">
                        <img id="image" class="card-img-top" src="<?php echo base_url('/resources/img/seriesthumb/+.png')?>" alt="Thumbnail">
                        <div id="middle" class="card-body">
                            <h5 id="text" class="card-title">+ Series</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('comuns/footer'); ?>