<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendario extends CI_Controller {

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
        }
        $this->data['contSearch'] = 'Serie/search';
    }

    public function index(){
        $query = $this->main_model->get_table('calendario');
        $this->data['calendario'] = $query;
        $this->load->view('calendario',$this->data);
    }

    public function addCalendario(){
        $this->checkLogin();
        $levelsNeeded = array(
            UPLPERM,
            MODPERM,
            ADMPERM
        );
        $this->checkPerms($levelsNeeded,$this->data['perms']);
        if(isset($_POST['submitcalendar'])){
            $idSerie = $this->uri->segment(3);
            $serie = $this->main_model->get_main_where_array('series','idSerie',$idSerie);
            $check = $this->main_model->get_main_where_array('calendario','idSerie',$idSerie);
            if(!empty($check)){
                $values = array(
                    'dayOfWeek' => $_POST['dataDaSemana']
                );
                $info = ' Editado Calendário de ' . $serie[0]['Titulo'];
                $valuesml = array(
                    'idUser' => $this->data['idUser'],
                    'info' => $info,
                    'status' => 1
                );
                $this->main_model->add('modlogs',$valuesml);
                $this->main_model->edit('idCalendario','calendario',$check[0]['idCalendario'],$values);
            }else{
                $values = array(
                    'idSerie' => $idSerie,
                    'dayOfWeek' => $_POST['dataDaSemana'],
                    'status' => 0
                );
                $info = ' Adicionado Calendário de ' . $serie[0]['Titulo'];
                $valuesml = array(
                    'idUser' => $this->data['idUser'],
                    'info' => $info,
                    'status' => 1
                );
                $this->main_model->add('modlogs',$valuesml);
                $this->main_model->add('calendario',$values);
            }
            redirect('Serie/seriesinfo/' . $idSerie);
        }
    }

    private function checkLogin(){
        if($this->login_model->isLoggedIn() == true){
            $user = $this->data['user'];
            /*$perms = $this->getPerms($user['perms']);
            $this->data['perms'] = $perms;*/
            $this->data['fotoPerfil'] = $user['FotoPerfil'];
            $this->data['idUser'] = $user['idUser'];
            $this->data['perms'] = $user['Permissoes'];
        }else{
            redirect();
        }
    }

    private function checkPermsV2($idAuthor,$idUser,$levelNeeded,$perms){
        if($perms == 4 || $perms == 5){

        }elseif(($idAuthor == $idUser) && $perms == 3){

        }else{
            redirect();
        }
    }

    private function checkPerms($levelNeeded,$perms){
        if(!in_array($perms,$levelNeeded)){
            redirect();
        }
    }


}
