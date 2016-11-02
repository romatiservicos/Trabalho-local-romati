<?php if ( !isset($evento) && isset($_SESSION['Responsavel'])) { ?>

<ul class="nav nav-sidebar">
    <li>
        <div class="text-center t">
            <h4><?php echo '<strong>' . $_SESSION['Responsavel']['NomeResponsavel'] . '</strong><br><small>Identificador: ' . $_SESSION['Responsavel']['idApp_Responsavel'] . '</small>' ?></h4>
        </div>
    </li>
</ul>

<ul class="nav nav-sidebar">
    <li <?php if (preg_match("/prontuario\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; //(.)+\/prontuario/   ?>>
        <a href="<?php echo base_url() . 'responsavel/prontuario/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="glyphicon glyphicon-user"> </span> Prontuário <span class="sr-only">(current)</span>
        </a>
    </li>

    <li <?php if (preg_match("/responsavel\/alterar\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; ///(.)+\/alterar/    ?>>
        <a href="<?php echo base_url() . 'responsavel/alterar/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="glyphicon glyphicon-edit"></span> Editar Dados
        </a>
    </li>

    <li <?php if (preg_match("/dependente\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; ?>>
        <a href="<?php echo base_url() . 'dependente/pesquisar/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="fa fa-user-plus"></span> Dependentes
        </a>
    </li>    
    
    <li <?php if (preg_match("/consulta\/cadastrar\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; ?>>
        <a href="<?php echo base_url() . 'consulta/cadastrar/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="glyphicon glyphicon-time"></span> Marcar Sessão
        </a>
    </li>

    <li <?php if (preg_match("/consulta\/listar\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; ?>>
        <a href="<?php echo base_url() . 'consulta/listar/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="glyphicon glyphicon-list"></span> Listar Sessões
        </a>
    </li>
	
    <li <?php if (preg_match("/tratamentos\/cadastrar\b/", $_SERVER['REQUEST_URI'])) echo 'class=active'; ?>>
        <a href="<?php echo base_url() . 'tratamentos/cadastrar/' . $_SESSION['Responsavel']['idApp_Responsavel']; ?>">
            <span class="glyphicon glyphicon-time"></span> Tratamentos
        </a>
    </li>	
    
</ul>

<?php } ?>