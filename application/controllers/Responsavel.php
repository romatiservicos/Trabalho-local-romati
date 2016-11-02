<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Responsavel extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        #$this->load->library(array('basico', 'Basico_model', 'form_validation'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model', 'Responsavel_model', 'Dependente_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');

        #$this->load->view('responsavel/nav_secundario');
    }

    public function index() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->load->view('responsavel/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

    public function cadastrar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
            'idApp_Responsavel',
            'NomeResponsavel',
            'DataNascimento',
            
            'Telefone1',
            'Telefone2',
            'Telefone3',
            
            'Sexo',
            'Endereco',
            'Bairro',
            'Municipio',
            'Obs',
            'idSis_Usuario',
            'Email',
            'RegistroFicha',
        ), TRUE));

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #$this->form_validation->set_rules('NomeResponsavel', 'Nome do Responsável', 'required|trim|is_unique_duplo[App_Responsavel.NomeResponsavel.DataNascimento.' . $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql') . ']');
        $this->form_validation->set_rules('NomeResponsavel', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');
        $this->form_validation->set_rules('Telefone1', 'Telefone1', 'required|trim');
        $this->form_validation->set_rules('Email', 'E-mail', 'trim|valid_email');

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Cadastrar Cliente';
        $data['form_open_path'] = 'responsavel/cadastrar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;
        
        if ($data['query']['Sexo'] || $data['query']['Endereco'] || $data['query']['Bairro'] || 
                $data['query']['Municipio'] || $data['query']['Obs'] || $data['query']['Email'] || $data['query']['RegistroFicha'])
            $data['collapse'] = '';
        else 
            $data['collapse'] = 'class="collapse"';            
        
        $data['sidebar'] = 'col-sm-3 col-md-2';
        $data['main'] = 'col-sm-7 col-md-8';        

        $data['tela'] = $this->load->view('responsavel/form_responsavel', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('responsavel/form_responsavel', $data);        
        } else {

            $data['query']['NomeResponsavel'] = trim(mb_strtoupper($data['query']['NomeResponsavel'], 'ISO-8859-1'));
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);
            $data['query']['idSis_Usuario'] = $_SESSION['log']['id'];
            $data['query']['idTab_Modulo'] = $_SESSION['log']['idTab_Modulo'];

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Responsavel'] = $this->Responsavel_model->set_responsavel($data['query']);

            if ($data['idApp_Responsavel'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('responsavel/form_responsavel', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Responsavel'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Responsavel', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                redirect(base_url() . 'responsavel/prontuario/' . $data['idApp_Responsavel'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Responsavel',
            'NomeResponsavel',
            'DataNascimento',
            
            'Telefone1',
            'Telefone2',
            'Telefone3',
            
            'Sexo',
            'Endereco',
            'Bairro',
            'Municipio',
            'Obs',
            'idSis_Usuario',
            'Email',
            'RegistroFicha',
        ), TRUE);

        if ($id) {
            $data['query'] = $this->Responsavel_model->get_responsavel($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #$this->form_validation->set_rules('NomeResponsavel', 'Nome do Responsável', 'required|trim|is_unique_duplo[App_Responsavel.NomeResponsavel.DataNascimento.' . $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql') . ']');
        $this->form_validation->set_rules('NomeResponsavel', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');
        $this->form_validation->set_rules('Telefone1', 'Telefone1', 'required|trim');
        $this->form_validation->set_rules('Email', 'E-mail', 'trim|valid_email');
        
        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Editar Dados';
        $data['form_open_path'] = 'responsavel/alterar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;
        
        if ($data['query']['Sexo'] || $data['query']['Endereco'] || $data['query']['Bairro'] || 
                $data['query']['Municipio'] || $data['query']['Obs'] || $data['query']['Email'] || $data['query']['RegistroFicha'])
            $data['collapse'] = '';
        else 
            $data['collapse'] = 'class="collapse"';        

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        $data['sidebar'] = 'col-sm-3 col-md-2 sidebar';
        $data['main'] = 'col-sm-7 col-sm-offset-3 col-md-8 col-md-offset-2 main';
        
        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('responsavel/form_responsavel', $data);
        } else {

            $data['query']['NomeResponsavel'] = trim(mb_strtoupper($data['query']['NomeResponsavel'], 'ISO-8859-1'));
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);
            $data['query']['idSis_Usuario'] = $_SESSION['log']['id'];          

            $data['anterior'] = $this->Responsavel_model->get_responsavel($data['query']['idApp_Responsavel']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Responsavel'], TRUE);

            if ($data['auditoriaitem'] && $this->Responsavel_model->update_responsavel($data['query'], $data['query']['idApp_Responsavel']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'responsavel/form_responsavel/' . $data['query']['idApp_Responsavel'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Responsavel', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                redirect(base_url() . 'responsavel/prontuario/' . $data['query']['idApp_Responsavel'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function excluir($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Responsavel',
            'submit'
                ), TRUE);

        if ($id) {
            $data['query'] = $this->Responsavel_model->get_responsavel($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
            $data['query']['ResponsavelDataNascimento'] = $this->basico->mascara_data($data['query']['ResponsavelDataNascimento'], 'barras');
        }

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Tem certeza que deseja excluir o registro abaixo?';
        $data['form_open_path'] = 'responsavel/excluir';
        $data['readonly'] = 'readonly';
        $data['disabled'] = 'disabled';
        $data['panel'] = 'danger';
        $data['metodo'] = 3;

        $data['tela'] = $this->load->view('responsavel/form_responsavel', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('responsavel/tela_responsavel', $data); 
        } else {

            if ($data['query']['idApp_Responsavel'] === FALSE) {
                $data['msg'] = '?m=2';
                $this->load->view('responsavel/form_responsavel', $data);
            } else {

                $data['anterior'] = $this->Responsavel_model->get_responsavel($data['query']['idApp_Responsavel']);
                $data['campos'] = array_keys($data['anterior']);

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], NULL, $data['campos'], $data['query']['idApp_Responsavel'], FALSE, TRUE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Responsavel', 'DELETE', $data['auditoriaitem']);

                $this->Responsavel_model->delete_responsavel($data['query']['idApp_Responsavel']);

                $data['msg'] = '?m=1';

                redirect(base_url() . 'responsavel' . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function pesquisar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Pesquisa', 'Pesquisa', 'required|trim|callback_get_responsavel');
        
        if ($this->input->get('start') && $this->input->get('end')) {
            //$data['start'] = substr($this->input->get('start'),0,-3);
            //$data['end'] = substr($this->input->get('end'),0,-3);
            $_SESSION['agenda']['HoraInicio'] = substr($this->input->get('start'),0,-3);
            $_SESSION['agenda']['HoraFim'] = substr($this->input->get('end'),0,-3);            
        }
        
        $data['titulo'] = "Pesquisar Cliente";
        
        $data['Pesquisa'] = $this->input->post('Pesquisa');
        //echo date('d/m/Y H:i:s', $data['start'],0,-3));
        
        #run form validation
        if ($this->form_validation->run() !== FALSE && $this->Responsavel_model->lista_responsavel($data['Pesquisa'], FALSE) === TRUE) {

            $data['query'] = $this->Responsavel_model->lista_responsavel($data['Pesquisa'], TRUE);
            
            if ($data['query']->num_rows() == 1) {
                $info = $data['query']->result_array();
                
                if ($_SESSION['agenda']) 
                    redirect('consulta/cadastrar/' . $info[0]['idApp_Responsavel'] );
                else
                    redirect('responsavel/prontuario/' . $info[0]['idApp_Responsavel'] );
                
                exit();
            } else {
                $data['list'] = $this->load->view('responsavel/list_responsavel', $data, TRUE);
            }
           
        }
       
        ($data['Pesquisa']) ? $data['cadastrar'] = TRUE : $data['cadastrar'] = FALSE;

        $this->load->view('responsavel/pesq_responsavel', $data);

        $this->load->view('basico/footer');
    }

    public function prontuario($id) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $_SESSION['Responsavel'] = $data['query'] = $this->Responsavel_model->get_responsavel($id, TRUE);
        #$data['query'] = $this->Paciente_model->get_paciente($prontuario, TRUE);
        $data['titulo'] = 'Prontuário ' . $data['query']['NomeResponsavel'];
        $data['panel'] = 'primary';
        $data['metodo'] = 4;
        
        $_SESSION['log']['idApp_Responsavel'] = $data['resumo']['idApp_Responsavel'] = $data['query']['idApp_Responsavel'];
        $data['resumo']['NomeResponsavel'] = $data['query']['NomeResponsavel'];

        $data['query']['Idade'] = $this->basico->calcula_idade($data['query']['DataNascimento']);
        $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
        
        if ($data['query']['Sexo'] == 1)
            $data['query']['profile'] = 'm';
        elseif ($data['query']['Sexo'] == 2)
            $data['query']['profile'] = 'f';
        else
            $data['query']['profile'] = 'o';
        
        $data['query']['Sexo'] = $this->Basico_model->get_sexo($data['query']['Sexo']);

        $data['query']['Telefone'] = $data['query']['Telefone1'];
        ($data['query']['Telefone2']) ? $data['query']['Telefone'] = $data['query']['Telefone'] . ' - ' . $data['query']['Telefone2'] : FALSE;
        ($data['query']['Telefone3']) ? $data['query']['Telefone'] = $data['query']['Telefone'] . ' - ' . $data['query']['Telefone3'] : FALSE;
        
        
        if ($data['query']['Municipio']) {
            $mun = $this->Basico_model->get_municipio($data['query']['Municipio']);
            $data['query']['Municipio'] = $mun['NomeMunicipio'] . '/' . $mun['Uf'];
        } else {
            $data['query']['Municipio'] = $data['query']['Uf'] = $mun['Uf'] = '';
        }

        $data['dependente'] = $this->Dependente_model->lista_dependente(TRUE);
        /*
          echo "<pre>";
          print_r($data['dependente']);
          echo "</pre>";
          exit();
        */
        if (!$data['dependente'])
            $data['list'] = FALSE;
        else
            $data['list'] = $this->load->view('dependente/list_dependente', $data, TRUE);
        
        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);     
        $this->load->view('responsavel/tela_responsavel', $data);

        $this->load->view('basico/footer');
    }

    function get_responsavel($data) {

        if ($this->Responsavel_model->lista_responsavel($data, FALSE) === FALSE) {
            $this->form_validation->set_message('get_responsavel', '<strong>Paciente</strong> não encontrado.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
