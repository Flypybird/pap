<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/ControladorAbstrato.php';

class Hub extends ControladorAbstrato {

    public function __construct(){
        parent::__construct();
        $this->load->library(array('session','parser','image_lib'));
        $this->load->helper(array('text','string','url','form'));
        $this->load->model('login_model');
        $this->load->model('main_model');

        if ($this->login_model->isLoggedIn()) {
            $this->data['user'] = $this->session->userdata('user');
            $this->data['estado'] = 1;
            $this->data['seg'] = FALSE;
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            $this->data['perms'] = $user['Permissoes'];
            $query = $this->main_model->get_main_where_array('notification','idUser',$user['idUser']);
            $this->data['notif'] = $query;
            $this->checkIfBanned($user['Permissoes']);
        }
        $this->data['contSearch'] = 'Hub/search';
    }

	public function index()
	{
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            //$perms = $this->getPerms($user['Permissoes']);
            //$this->data['perms'] = $perms;
            $this->data['idUser'] = $user['idUser'];
        }
        $this->data['titulo'] = 'AnimePrimera Hub';
        $this->data['staffpost'] = $this->main_model->get_main_where_array('compost','isStaff',1);
        $this->data['posts'] = $this->main_model->get_table_limited('compost',9,'idCompost');
        $query = $this->main_model->get_table_orderby('compost','votes', 15);
        $this->data['comfav'] = $query;
        $this->load->view('hub',$this->data);
	}

    public function allPost(){
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            //$perms = $this->getPerms($user['Permissoes']);
            //$this->data['perms'] = $perms;
            $this->data['idUser'] = $user['idUser'];
        }
        $this->data['titulo'] = 'Posts Recentes';
        $this->data['h3title'] = 'Posts Recentes';
        $this->data['posts'] = $this->main_model->get_table_limited('compost',50,'idCompost');
        $this->load->view('allPost',$this->data);
    }

	public function hubinfo(){
        $idCompost = $this->uri->segment(3);
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            //$perms = $this->getPerms($user['Permissoes']);
            //$this->data['perms'] = $perms;
            $this->data['idUser'] = $user['idUser'];
            $arrayC = array(
                'idCompost' => $idCompost,
                'idUser' => $this->data['idUser']
            );
            if($this->main_model->double_get_main_where_array('compostvotes',$arrayC)){
                $this->data['ratingC'] = 'seguirDone';
            }
        }
        $query = $this->main_model->get_main_where_array('compost','idCompost',$idCompost);
        $this->data['titulo'] = $query[0]['titulo'];
        $this->data['query'] = $query;
        $this->data['idCompost'] = $idCompost;
        $queryC = $this->main_model->get_main_where('comentariocompost','idCompost',$idCompost);
        $queryA = $this->main_model->get_main_where_array('user','idUser',$query[0]['idUser']);
        $this->data['username'] = $queryA[0]['Username'];
        $this->data['pfp'] = $queryA[0]['FotoPerfil'];
        $this->data['idAuthor'] = $queryA[0]['idUser'];
        $gquery = $this->main_model->get_main_where_array('compostvotes','idCompost',$idCompost);
        $this->data['gostos'] = count($gquery);
        if(!empty($queryC)){
            $queryUserCom = $this->main_model->get_both_main_whereV2('comentariocompost','user','comentariocompost.idUser = user.idUser','comentariocompost.idCompost =',$idCompost);
            //$queryUserCom = $this->db->query('SELECT * FROM user u INNER JOIN comentario c ON u.idUser = c.idUser WHERE u.idUser =' . $queryComentarios[0]->idUser);
            $this->data['comentarios'] = $queryUserCom;
        }else{
            $this->data['comentarios'] = array();
        }

        $this->load->view('hubinfo',$this->data);
    }

	public function criarPost(){
        $this->checkLogin('hub','Faça Login primeiro.');
        $this->data['titulo'] = 'Criar Post';
        if(isset($_POST['Criar'])){
            $values = array(
                'titulo' => $_POST['titulo'],
                'descricao' => $_POST['descricao'],
                'idUser' => $this->data['idUser'],
                'status' => 1
            );
            if(isset($_POST['staffpost'])){
                $valuess = array(
                    'isStaff' => 1
                );
            }else{
                $valuess = array(
                    'isStaff' => 0
                );
            }
            $values = array_merge($values,$valuess);
            if(isset($_POST['hasVideo'])){
                $video = $this->UploadVideo($_POST);
                $url = $video['video_path'] . $video['video_name'];
                $valuesv = array(
                    'video' => $url
                );
                $values = array_merge($values,$valuesv);
            }
            if(isset($_POST['hasImage'])){
                $uploadFile = $this->UploadFile('thumbnail');
                $e = $uploadFile['fileData'];
                $imgname = $e['file_name'];
                $valuesi = array(
                    'img' => $imgname
                );
                $values = array_merge($values,$valuesi);
            }
            $this->main_model->add('compost',$values);
            redirect('hub');
        }
        $this->load->view('criarPost',$this->data);
    }

    public function editarPost(){
        $this->data['titulo'] = 'Editar Post';
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            if(isset($_POST['Editar'])){
                $levelsNeeded = array(
                    MODPERM,
                    ADMPERM
                );
                $this->checkPermsV2($_POST['idAuthor'],$user['idUser'],$levelsNeeded,$this->data['perms']);
                $values = array(
                    'Titulo' => $_POST['titulo'],
                    'Descricao' => $_POST['descricao']
                );
                $this->main_model->edit('idCompost','compost',$_POST['idCompost'],$values);
                redirect('hub/hubinfo/' . $_POST['idCompost']);
            }else{
                $idCompost = $this->uri->segment(3);
                $this->data['idCompost'] = $idCompost;
                $this->data['idUser'] = $user['idUser'];
                $this->data['compost'] = $this->main_model->get_main_where_array('compost','idCompost',$idCompost);
                $this->load->view('editarPost',$this->data);
            }
        }
    }

    public function removerPost(){
        $this->data['titulo'] = 'Remover Post';
        $this->checkLogin('hub','Faça Login primeiro');
        $idCompost = $this->uri->segment(3);
        $query = $this->main_model->get_main_where_array('compost','idCompost',$idCompost);
        $levelsNeeded = array(
            MODPERM,
            ADMPERM
        );
        $this->checkPermsV2($query[0]['idUser'],$this->data['idUser'],$levelsNeeded,$this->data['perms']);
        //$query = $this->main_model->get_main_where_array('comentariocompost','id')
        $msg = 'Removido ' . $query[0]['titulo'];
        $valuesml = array(
            'idUser' => $this->data['idUser'],
            'info' => $msg,
            'status' => 1
        );
        $this->main_model->add('modlogs',$valuesml);
        $this->main_model->delete('idCompost','comentariocompost',$idCompost);
        $this->main_model->delete('idCompost','compostvotes',$idCompost);
        $this->main_model->delete('idCompost','compost',$idCompost);
        $this->main_model->delete('idCompost','notification',$idCompost);
        redirect('hub');
    }



    public function likePost(){
        $idCompost = $this->uri->segment(3);
        $this->checkLogin('hub/hubinfo/'.$idCompost,'Faça Login primeiro.');
        $query = $this->main_model->get_main_where_array('compost','idCompost',$idCompost);
        $arrayC = array(
            'idCompost' => $idCompost,
            'idUser' => $this->data['idUser']
        );
        if(!$this->main_model->double_get_main_where_array('compostvotes',$arrayC)){
            $values = array(
                'idUser' => $this->data['idUser'],
                'idCompost' => $idCompost
            );
            $valuesc = array(
                'votes' => $query[0]['votes'] + 1
            );
            $this->main_model->add('compostvotes', $values);
            $this->main_model->edit('idCompost', 'compost', $idCompost, $valuesc);
        }else{
            $where = array(
                'idUser' => $this->data['idUser'],
                'idCompost' => $idCompost
            );
            $valuesc = array(
                'votes' => $query[0]['votes'] - 1
            );
            $this->main_model->deleteA('compostvotes',$where);
            $this->main_model->edit('idCompost','compost',$idCompost,$valuesc);
        }
        redirect('hub/hubinfo/'.$idCompost);
    }

    public function search(){
        $this->data['titulo'] = 'Procurar Post';
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            //$perms = $this->getPerms($user['Permissoes']);
            //$this->data['perms'] = $perms;
            $this->data['idUser'] = $user['idUser'];
        }
        $query = $this->main_model->get_table('compost');
        if(!empty($_POST['animename'])){
            $searchitem = $_POST['animename'];
            $cont = 0;
            $results = array();
            $seriesres = array();
            foreach ($query as $serie){
                //print_r($serie);
                //$results = array_search($searchitem,$serie);
                if(strpos($serie['titulo'], $searchitem) !== false){
                    $results[$cont] = $serie['idCompost'];
                    $cont += 1;
                }
            }
            if(!empty($results)){
                for($i = 0; $i <= $cont - 1; $i++){
                    $seriesres = array_merge($seriesres,$this->main_model->get_main_where_array('compost','idCompost',$results[$i]));
                }
            }
            $this->data['n'] = $cont;
            $this->data['posts'] = $seriesres;
            $this->load->view('searchCompost',$this->data);
        }else{
            redirect();
        }
    }

    private function UploadVideo($inputFileName){
        if ($this->input->post('video_upload')) {
            //set preferences
            //file upload destination
            $upload_path =  "resources/vid/";
            $config['upload_path'] = $upload_path;
            //allowed file types. * means all types
            $config['allowed_types'] = '*';
            //allowed max file size. 0 means unlimited file size
            if($this->data['perms'] < 2)
                $config['max_size'] = '0';
            else{
                $config['max_size'] = '1000000';
            }
            //max file name size
            $config['max_filename'] = '255';
            //whether file name should be encrypted or not
            $config['encrypt_name'] = FALSE;
            //store video info once uploaded
            $video_data = array();
            //check for errors
            $is_file_error = FALSE;
            //check if file was selected for upload
            if (!$_FILES) {
                $is_file_error = TRUE;
                $this->handle_error('Select a video file.');
            }
            //if file was selected then proceed to upload
            if (!$is_file_error) {
                //load the preferences
                $this->load->library('upload', $config);
                //check file successfully uploaded. 'video_name' is the name of the input
                if (!$this->upload->do_upload('video_name')) {
                    //if file upload failed then catch the errors
                    $this->handle_error($this->upload->display_errors());
                    $is_file_error = TRUE;
                    $this->session->set_flashdata('error',$this->upload->display_errors());
                    redirect();
                } else {
                    //store the video file info
                    $video_data = $this->upload->data();
                }
            }
            // There were errors, you have to delete the uploaded video
            if ($is_file_error) {
                if ($video_data) {
                    $file = $upload_path . $video_data['file_name'];
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            } else {
                $data['video_name'] = $video_data['file_name'];
                $data['video_path'] = $upload_path;
                $data['video_type'] = $video_data['file_type'];
                $this->handle_success('Video was successfully uploaded to direcoty <strong>' . $upload_path . '</strong>.');
            }
        }
        //load the error and success messages
        $data['errors'] = $this->error;
        $data['success'] = $this->success;
        return $data;
    }

    private function UploadFile($inputFileName)
    {
        /*
         * O CodeIgniter possui uma biblioteca nativa para trabalhar com upload de arquivos, chamada File Uploading.
         */
        $this->load->library('upload');
        //Definimos um caminho para upload, neste caso será na raiz /app2
        $path = "./resources/img/compost/";

        //Definimos as configurações para o upload

        //Determinamos o path para gravar o arquivo
        $config['upload_path'] = $path;

        //Definimos os tipos de arquivos suportados
        $config['allowed_types'] = 'jpg|png|gif|pdf|zip|rar|doc|xls';

        //Definimos o maximo permitido
        //Php.ini definimos os tamanhos permitidos
        //post_max_size=15M -> pelo POST
        // upload_max_size=15M // Por Upload
        $config['max_size'] = '51120';//em KB

        //Definimos que o nome do arquivo será criptografado
        $config['encrypt_name'] = TRUE;

        //Verificamos se o diretório existe se não existe criamos com permissão de leitura e escrita
        if (!is_dir($path))
            mkdir($path, 0777, $recursive = true);

        //Setamos as configurações para a library upload
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($inputFileName)) {
            //Em caso de erro retornamos os mesmos para uma variável e enviamos para a view
            $data['error'] = true;
            $data['message'] = $this->upload->display_errors();
        } else {
            $data['error'] = false;

            //Recuperamos os dados do arquivo e enviamos para o array da view
            $data['fileData'] = $this->upload->data();
            $arquivoPath = $path . "/" . $data['fileData']['file_name'];
            //Passando para o array $data
            $data['urlArquivo'] = base_url($arquivoPath);

            //Definimos a URL para download
            $downloadPath = base_url('resources/img/') . $data['fileData']['file_name'];
            //Passando para o Array Data
            $data['urlDownload'] = base_url($downloadPath);


        }
        return $data;
    }

    //appends all error messages
    private function handle_error($err) {
        $this->error .= $err . "\r\n";
    }

    //appends all success messages
    private function handle_success($succ) {
        $this->success .= $succ . "\r\n";
    }

}
