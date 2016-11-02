<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model', 'Consulta_model', 'Responsavel_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');
    }

    public function index() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->load->view('consulta/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

    public function cadastrar($idApp_Responsavel = NULL, $idApp_Dependente = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
            'idApp_Consulta',
            'idApp_Agenda',
            'idApp_Responsavel',
            'Data',
            'HoraInicio',
            'HoraFim',
            'Paciente',
            'idTab_TipoConsulta',
            'idApp_Dependente',
            'idApp_Profissional',
            'Procedimento',
            'Obs',
                ), TRUE));

        $data['servico'] = quotes_to_entities($this->input->post(array(
            'SCount',

            'idTab_Servico1',
            'ValorVenda1',
        ), TRUE));        

        $data['produto'] = quotes_to_entities($this->input->post(array(
            'PCount',
            
            'idTab_Produto1',
            'ValorProduto1',
            'Quantidade1',
        ), TRUE));     
        
        //echo '==============================================>>>>>>>>>>'.$data['query']['SCount'] . ' :: ' . $data['query']['PCount'];
        //echo $this->input->post('idTab_Servico2');
        
        $sq = '';
        if ($data['servico']['SCount']>1) {
            
            $j=0;
            for($i=0;$i<=$data['servico']['SCount'];$i++) {
                
                if ($this->input->post('idTab_Servico'.$i)) {
                    $data['servico']['idTab_Servico'][$j] = $this->input->post('idTab_Servico'.$i);
                    $data['servico']['ValorVenda'][$j] = $this->input->post('ValorVenda'.$i);
                    
                    $sq = $sq . '("' . $this->input->post('idTab_Servico'.$i) . '", ';
                    //$sq = $sq . '\'' . $this->input->post('ValorVenda'.$i) . '\'), ';                    
                    $sq = $sq . '"0.00"), ';
                    
                    $j++;
                }
                                
            }
                
        }
        else {
            if ($this->input->post('idTab_Servico1')) {
                $data['servico']['idTab_Servico'][1] = $this->input->post('idTab_Servico1');
                $data['servico']['ValorVenda'][1] = $this->input->post('ValorVenda1');
                
                $sq = $sq . '("' . $this->input->post('idTab_Servico1') . '", ';
                //$sq = $sq . '\'' . $this->input->post('ValorVenda1') . '\'), ';                 
                $sq = $sq . '"0.00"), ';
            }
        }
        $sq = substr($sq, 0, strlen($sq)-2);
        
        $pq = '';
        if ($data['produto']['PCount']>1) {
            
            $j=0;
            for($i=0;$i<=$data['produto']['PCount'];$i++) {
                
                if ($this->input->post('idTab_Produto'.$i)) {
                    $data['produto']['idTab_Produto'][$j] = $this->input->post('idTab_Produto'.$i);
                    $data['produto']['ValorProduto'][$j] = $this->input->post('ValorProduto'.$i);
                    $data['produto']['Quantidade'][$j] = $this->input->post('Quantidade'.$i);
                    
                    $pq = $pq . '(\'' . $this->input->post('idTab_Produto'.$i) . '\', ';
                    //$pq = $pq . '\'' . $this->input->post('ValorProduto'.$i) . '\', ';
                    $pq = $pq . '\'0.00\', ';
                    $pq = $pq . '\'' . $this->input->post('Quantidade'.$i) . '\'), ';
                    
                    
                    $j++;
                }
                                
            }
                
        }
        else {
            if ($this->input->post('idTab_Produto1')) {
                $data['produto']['idTab_Produto'][1] = $this->input->post('idTab_Produto1');
                $data['produto']['ValorProduto1'][1] = $this->input->post('ValorProduto1');
                $data['produto']['Quantidade1'][1] = $this->input->post('Quantidade1');

                $pq = $pq . '(\'' . $this->input->post('idTab_Produto1') . '\', ';
                //$pq = $pq . '\'' . $this->input->post('ValorProduto1') . '\', ';
                $pq = $pq . '\'0.00\', ';
                $pq = $pq . '\'' . $this->input->post('Quantidade1') . '\'), ';                
            }
        }
        $pq = substr($pq, 0, strlen($pq)-2);
        //exit();        
        /*
              echo '<br>';
              echo "<pre>";
              print_r($data['servico']['idTab_Servico']);
              echo "</pre>";
              exit();        
        */
        
        if ($idApp_Responsavel) {
            $data['query']['idApp_Responsavel'] = $idApp_Responsavel;
            $_SESSION['Responsavel'] = $this->Responsavel_model->get_responsavel($idApp_Responsavel, TRUE);
        }        
        
        if ($idApp_Dependente) {
            $data['query']['idApp_Dependente'] = $idApp_Dependente;
            $data['query']['Paciente'] = 'D';
        }
            
        if (isset($_SESSION['agenda'])) {
            $data['query']['Data'] = date('d/m/Y', $_SESSION['agenda']['HoraInicio']);
            $data['query']['HoraInicio'] = date('H:i', $_SESSION['agenda']['HoraInicio']);
            $data['query']['HoraFim'] = date('H:i', $_SESSION['agenda']['HoraFim']);
        }

        #Ver uma solução melhor para este campo
        (!$data['query']['Paciente']) ? $data['query']['Paciente'] = 'R' : FALSE;       
        
        $data['radio'] = array(
            'Paciente' => $this->basico->radio_checked($data['query']['Paciente'], 'Paciente', 'RD'),
        );        
        
        ($data['query']['Paciente'] == 'D') ?
            $data['div']['Paciente'] = '' : $data['div']['Paciente'] = 'style="display: none;"';        
                
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');
        $this->form_validation->set_rules('idTab_Servico1', 'Produto', 'required|trim');
        $this->form_validation->set_rules('idTab_Produto1', 'Serviço', 'required|trim');
        $this->form_validation->set_rules('idApp_Profissional', 'Profissional', 'required|trim');
        if ($data['query']['Paciente'] == 'D')
            $this->form_validation->set_rules('idApp_Dependente', 'Dependente', 'required|trim');

        $data['resumo'] = $this->Responsavel_model->get_responsavel($data['query']['idApp_Responsavel']);

        $data['select']['TipoConsulta'] = $this->Basico_model->select_tipo_consulta();
        $data['select']['Profissional'] = $this->Basico_model->select_profissional();
        $data['select']['Servico'] = $this->Basico_model->select_servico();
        $data['select']['Produto'] = $this->Basico_model->select_produto();
        $data['select']['Dependente'] = $this->Consulta_model->select_dependente_responsavel($data['query']['idApp_Responsavel']);

        $data['select']['Paciente'] = array (
            'R' => 'O Próprio',
            'D' => 'Dependente',
        );
        
        $data['titulo'] = 'Marcar Sessão';
        $data['form_open_path'] = 'consulta/cadastrar';
        $data['panel'] = 'primary';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['metodo'] = 1;

        $data['datepicker'] = 'DatePicker';
        $data['timepicker'] = 'TimePicker';

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
        //if (1==1) {
            $this->load->view('consulta/form_consulta', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];
            $data['query']['idTab_Status'] = 1;
            $data['query']['idTab_Modulo'] = $_SESSION['log']['idTab_Modulo'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);
            
            /*
             * FALTA FAZER UM ESQUEMA PARA ARMAZENAR NO LOG OS DADOS DOS CAMPOS ADICIONADOS DINAMICAMENTE
             */
            
            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Consulta'] = $this->Consulta_model->set_consulta($data['query']);

            unset($_SESSION['Agenda']);
            
            if ($data['idApp_Consulta'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('consulta/form_consulta', $data);
            } else {
                
                $this->Consulta_model->set_dados_dinamicos('App_Servico','idTab_Servico, ValorVenda',$sq);
                $this->Consulta_model->set_dados_dinamicos('App_Produto','`idTab_Produto`, `ValorProduto`, `Quantidade`',$pq);
                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Consulta'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                //redirect(base_url() . 'responsavel/prontuario/' . $data['query']['idApp_Responsavel'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar($idApp_Responsavel = FALSE, $idApp_Consulta = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Consulta',
            'idApp_Agenda',
            'idApp_Responsavel',
            'Data',
            'HoraInicio',
            'HoraFim',
            'idTab_Status',
            'Paciente',
            'idApp_Dependente',
            'idApp_Profissional',
            'Procedimento',
            'Obs',
                ), TRUE);

        if ($idApp_Responsavel) {
            $data['query']['idApp_Responsavel'] = $idApp_Responsavel;
            $_SESSION['Responsavel'] = $this->Responsavel_model->get_responsavel($idApp_Responsavel, TRUE);
        }
        
        if ($idApp_Consulta) {
            $data['query']['idApp_Responsavel'] = $idApp_Responsavel;
            $data['query'] = $this->Consulta_model->get_consulta($idApp_Consulta);

            $dataini = explode(' ', $data['query']['DataInicio']);
            $datafim = explode(' ', $data['query']['DataFim']);

            $data['query']['Data'] = $this->basico->mascara_data($dataini[0], 'barras');
            $data['query']['HoraInicio'] = substr($dataini[1], 0, 5);
            $data['query']['HoraFim'] = substr($datafim[1], 0, 5);
        }
        else {
            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];         
        }
        

        if ($data['query']['DataFim'] < date('Y-m-d H:i:s', time())) {
            $data['readonly'] = 'readonly';
            $data['datepicker'] = '';
            $data['timepicker'] = '';
        } else {
            $data['readonly'] = '';
            $data['datepicker'] = 'DatePicker';
            $data['timepicker'] = 'TimePicker';
        }

        #echo $data['query']['DataInicio'];
        #$data['query']['idApp_Agenda'] = 1;


        #Ver uma solução melhor para este campo
        (!$data['query']['Paciente']) ? $data['query']['Paciente'] = 'R' : FALSE;       
        
        $data['radio'] = array(
            'Paciente' => $this->basico->radio_checked($data['query']['Paciente'], 'Paciente', 'RD'),
        );        
        
        ($data['query']['Paciente'] == 'D') ?
            $data['div']['Paciente'] = '' : $data['div']['Paciente'] = 'style="display: none;"';               
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');
        #$this->form_validation->set_rules('idTab_TipoConsulta', 'Tipo de Consulta', 'required|trim');
        $this->form_validation->set_rules('idApp_Profissional', 'Profissional', 'required|trim');       
        if ($data['query']['Paciente'] == 'D')
            $this->form_validation->set_rules('idApp_Dependente', 'Dependente', 'required|trim');

        $data['select']['Status'] = $this->Basico_model->select_status();
        $data['select']['TipoConsulta'] = $this->Basico_model->select_tipo_consulta();
        $data['select']['Profissional'] = $this->Basico_model->select_profissional();
        $data['select']['Dependente'] = $this->Consulta_model->select_dependente_responsavel($data['query']['idApp_Responsavel']);        

        $data['select']['Paciente'] = array (
            'R' => 'O Próprio',
            'D' => 'Dependente',
        );
        
        $data['resumo'] = $this->Responsavel_model->get_responsavel($data['query']['idApp_Responsavel']);

        //echo '<br><br><br><br>================================== '.$data['query']['idTab_Status'];
        
        $data['titulo'] = 'Editar Sessão';
        $data['form_open_path'] = 'consulta/alterar';
        #$data['readonly'] = '';
        #$data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('consulta/form_consulta', $data);
        } else {
            
            #echo '<br><br><br><br>================================== '.$data['query']['idTab_Status'];
            #exit();
            
            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');
            //exit();

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['anterior'] = $this->Consulta_model->get_consulta($data['query']['idApp_Consulta']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Consulta'], TRUE);

            unset($_SESSION['Agenda']);
            
            if ($data['auditoriaitem'] && $this->Consulta_model->update_consulta($data['query'], $data['query']['idApp_Consulta']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Consulta'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                //redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Responsavel'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function listar($idApp_Responsavel = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        if ($idApp_Responsavel) {
            $data['resumo'] = $this->Responsavel_model->get_responsavel($idApp_Responsavel);
            $_SESSION['Responsavel'] = $this->Responsavel_model->get_responsavel($idApp_Responsavel, TRUE);
        }
        
        $data['titulo'] = 'Listar Sessões';
        $data['panel'] = 'primary';
        $data['novo'] = '';
        $data['metodo'] = 4;

        $data['query'] = array();
        $data['proxima'] = $this->Consulta_model->lista_consulta_proxima($idApp_Responsavel);
        $data['anterior'] = $this->Consulta_model->lista_consulta_anterior($idApp_Responsavel);

        #$data['tela'] = $this->load->view('consulta/list_consulta', $data, TRUE);
        #$data['resumo'] = $this->Responsavel_model->get_responsavel($data['Responsavel']['idApp_Responsavel']);
        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);        

        $this->load->view('consulta/list_consulta', $data);

        $this->load->view('basico/footer');
    }

    /*
     * Cadastrar/Alterar Eventos
     */

    public function cadastrar_evento($idApp_Responsavel = NULL, $idApp_Agenda = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
                    'idApp_Consulta',
                    'idApp_Agenda',
                    'Data',
                    'HoraInicio',
                    'HoraFim',
                    'Evento',
                    'Obs',
                        ), TRUE));

        if ($this->input->get('start') && $this->input->get('end')) {
            $data['query']['Data'] = date('d/m/Y', substr($this->input->get('start'), 0, -3));
            $data['query']['HoraInicio'] = date('H:i', substr($this->input->get('start'), 0, -3));
            $data['query']['HoraFim'] = date('H:i', substr($this->input->get('end'), 0, -3));
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');

        $data['titulo'] = 'Agendar Evento';
        $data['form_open_path'] = 'consulta/cadastrar_evento';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;
        $data['evento'] = 1;

        $data['readonly'] = '';
        $data['datepicker'] = 'DatePicker';
        $data['timepicker'] = 'TimePicker';

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('consulta/form_evento', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];
            $data['query']['idTab_Modulo'] = $_SESSION['log']['idTab_Modulo'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Consulta'] = $this->Consulta_model->set_consulta($data['query']);

            if ($data['idApp_Consulta'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('consulta/form_consulta', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Consulta'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                //redirect(base_url() . 'responsavel/prontuario/' . $data['query']['idApp_Responsavel'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar_evento($idApp_Consulta = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Consulta',
            'idApp_Agenda',
            'Data',
            'HoraInicio',
            'HoraFim',
            'Evento',
            'Obs',
                ), TRUE);


        if ($idApp_Consulta) {
            $data['query'] = $this->Consulta_model->get_consulta($idApp_Consulta);

            $dataini = explode(' ', $data['query']['DataInicio']);
            $datafim = explode(' ', $data['query']['DataFim']);

            $data['query']['Data'] = $this->basico->mascara_data($dataini[0], 'barras');
            $data['query']['HoraInicio'] = substr($dataini[1], 0, 5);
            $data['query']['HoraFim'] = substr($datafim[1], 0, 5);
        }

        if ($data['query']['DataFim'] < date('Y-m-d H:i:s', time())) {
            $data['readonly'] = 'readonly';
            $data['datepicker'] = '';
            $data['timepicker'] = '';
        } else {
            $data['readonly'] = '';
            $data['datepicker'] = 'DatePicker';
            $data['timepicker'] = 'TimePicker';
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('Data', 'Data', 'required|trim|valid_date');
        $this->form_validation->set_rules('HoraInicio', 'Hora Inicial', 'required|trim|valid_hour');
        $this->form_validation->set_rules('HoraFim', 'Hora Final', 'required|trim|valid_hour|valid_periodo_hora[' . $data['query']['HoraInicio'] . ']');

        $data['titulo'] = 'Agendar Evento';
        $data['form_open_path'] = 'consulta/alterar_evento';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;
        $data['evento'] = 1;

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('consulta/form_evento', $data);
        } else {

            $data['query']['DataInicio'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraInicio'];
            $data['query']['DataFim'] = $this->basico->mascara_data($data['query']['Data'], 'mysql') . ' ' . $data['query']['HoraFim'];

            $data['redirect'] = '&gtd=' . $this->basico->mascara_data($data['query']['Data'], 'mysql');
            //exit();

            unset($data['query']['Data'], $data['query']['HoraInicio'], $data['query']['HoraFim']);

            $data['anterior'] = $this->Consulta_model->get_consulta($data['query']['idApp_Consulta']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Consulta'], TRUE);

            if ($data['auditoriaitem'] && $this->Consulta_model->update_consulta($data['query'], $data['query']['idApp_Consulta']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Consulta'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Consulta', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                //redirect(base_url() . 'consulta/listar/' . $data['query']['idApp_Responsavel'] . $data['msg'] . $data['redirect']);
                redirect(base_url() . 'agenda' . $data['msg'] . $data['redirect']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

}
