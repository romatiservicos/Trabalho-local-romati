<?php

#modelo que verifica usuário e senha e loga o usuário no sistema, criando as sessões necessárias

defined('BASEPATH') OR exit('No direct script access allowed');

class Consulta_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('basico');
    }

    public function set_consulta($data) {

        $query = $this->db->insert('App_Consulta', $data);

        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return $this->db->insert_id();
    }

    public function set_dados_dinamicos($table, $fields, $data) {
        /*
        $i=0;
        $q='';
        while ($data['idTab_Servico'][$i]) {
            $q = $q . '("' . $this->db->escape($this->input->post('idTab_Servico'.$i)) . '", ';
            $q = $q . '"0.00"), ';            
            $i++;
        }
        echo $q = substr($q, 0, strlen($q)-2) . '<br>';
                
        //echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($data);
          echo "</pre>";
          exit ();   
        */
        
        $query = $this->db->query('INSERT INTO ' . $table . ' (' . $fields . ') VALUES ' . $data);
        //$query = $this->db->query('INSERT INTO App_Servico (idTab_Servico, ValorVenda) VALUES ' . $data);
        //$this->db->insert($table, $data);
                
        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return $this->db->insert_id();
    }    
    
    public function get_consulta($data) {
        $query = $this->db->query('SELECT * FROM App_Consulta WHERE idApp_Consulta = ' . $data);
        $query = $query->result_array();

        return $query[0];
    }

    public function update_consulta($data, $id) {

        unset($data['Id']);
        $query = $this->db->update('App_Consulta', $data, array('idApp_Consulta' => $id));
        /*
          echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit ();
         */
        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return TRUE;
    }

    public function delete_consulta($data) {
        $query = $this->db->delete('App_Consulta', array('idApp_Consulta' => $data));

        if ($this->db->affected_rows() === 0)
            return FALSE;
        else
            return TRUE;
    }

    public function lista_consulta_proxima($data) {

        $query = $this->db->query('SELECT '
                . 'C.idApp_Consulta, '
                . 'C.idApp_Agenda, '
                . 'C.idApp_Responsavel, '
                . 'C.DataInicio, '
                . 'C.DataFim, '
                . 'S.idTab_Status, '
                . 'S.Status, '
                . 'D.NomeDependente, '
                . 'V.NomeProfissional, '
                . 'C.Procedimento, '
                . 'C.Paciente, '
                . 'C.Obs '
            . 'FROM '
                . 'App_Consulta AS C '
                    . 'LEFT JOIN app.App_Dependente AS D ON C.idApp_Dependente = D.idApp_Dependente, '
                . 'Tab_Status AS S, '                
                . 'App_Profissional AS V '
            . 'WHERE '
                . 'C.idApp_Responsavel = ' . $data . ' AND '
                . '(C.DataInicio >= "' . date('Y-m-d H:i:s', time()) . '" OR ('
                . 'C.DataInicio < "' . date('Y-m-d H:i:s', time()) . '" AND '
                . 'C.DataFim >= "' . date('Y-m-d H:i:s', time()) . '") ) AND '
                . 'C.idTab_Status = S.idTab_Status AND '
                . 'C.idApp_Profissional = V.idApp_Profissional '
            . 'ORDER BY C.DataInicio ASC ');
        
        if ($query->num_rows() === 0)
            return FALSE;
        else
            return $query;
    }

    public function lista_consulta_anterior($data) {

        $query = $this->db->query('SELECT '
                . 'C.idApp_Consulta, '
                . 'C.idApp_Agenda, '
                . 'C.idApp_Responsavel, '
                . 'C.DataInicio, '
                . 'C.DataFim, '
                . 'S.idTab_Status, '
                . 'S.Status, '
                . 'D.NomeDependente, '
                . 'V.NomeProfissional, '
                . 'C.Procedimento, '
                . 'C.Paciente, '
                . 'C.Obs '
            . 'FROM '
                . 'App_Consulta AS C '
                    . 'LEFT JOIN app.App_Dependente AS D ON C.idApp_Dependente = D.idApp_Dependente, '
                . 'Tab_Status AS S, '
                . 'App_Profissional AS V '
            . 'WHERE '
                . 'C.idApp_Responsavel = ' . $data . ' AND '
                . 'C.DataFim < "' . date('Y-m-d H:i:s', time()) . '" AND '
                . 'C.idTab_Status = S.idTab_Status AND '
                . 'C.idApp_Profissional = V.idApp_Profissional '
            . 'ORDER BY C.DataInicio ASC ');
        /*
          echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit ();
        */
        
        if ($query->num_rows() === 0)
            return FALSE;
        else
            return $query;
    }

    public function select_dependente_responsavel($data) {

        $q = 'SELECT '
                . 'idApp_Dependente, '
                . 'NomeDependente '
                . 'FROM '
                . 'App_Dependente '
                . 'WHERE '
                . 'idApp_Responsavel = ' . $data . ' '
                . 'ORDER BY NomeDependente ASC ';
        
        if ($data === TRUE) {
            $array = $this->db->query($q);
        } else {
            $query = $this->db->query($q);
            
            $array = array();
            foreach ($query->result() as $row) {
                $array[$row->idApp_Dependente] = $row->NomeDependente;
            }
        }

        return $array;
    }            
            
}
