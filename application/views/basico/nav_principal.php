<nav class="navbar navbar-inverse navbar-fixed-top">

    <div class="row">
        <div class="col-md-8">

            <ul class="nav navbar-nav">
                <li class="active"><a class="navbar-brand" href="<?php echo base_url(); ?>agenda">AGENDA</a></li>
                <li>
                    <?php echo form_open(base_url() . 'responsavel/pesquisar', 'class="navbar-form navbar-left"'); ?>
                    <div class="input-group">
                        <input type="text" placeholder="Pesquisar" class="form-control" name="Pesquisa" value="">
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>                    
                    </form>
                </li>

                <li class="dropdown <?php if (substr(uri_string(), 0, strpos(uri_string(), '/')) == 'dependente') { echo 'active'; } ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cliente <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url() ?>responsavel/cadastrar">Cadastrar</a></li>
                        <li><a href="<?php echo base_url() ?>responsavel/pesquisar">Pesquisar</a></li>
                        <li><a href="<?php echo base_url() ?>responsavel/pesquisar">Editar Dados</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo base_url() ?>responsavel/pesquisar">Excluir</a></li>
                    </ul>
                </li>                    

                <li class="dropdown <?php if (substr(uri_string(), 0, strpos(uri_string(), '/')) == 'consulta') { echo 'active'; } ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sessão <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url() ?>responsavel/pesquisar">Marcar Sessão</a></li>
                        <li><a href="<?php echo base_url() ?>responsavel/pesquisar">Editar Sessão</a></li>
                    </ul>
                </li>   
                
                <li class="dropdown <?php if (substr(uri_string(), 0, strpos(uri_string(), '/')) == 'tabelas') { echo 'active'; } ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Tabelas <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url() ?>tabelas/profissional">Profissional</a></li>
                        <li><a href="<?php echo base_url() ?>tabelas/servico">Serviços</a></li>
                        <li><a href="<?php echo base_url() ?>tabelas/produto">Produtos</a></li>
                    </ul>
                </li>                 

                <!--<li class="active"><a class="navbar-brand" href="<?php echo base_url(); ?>teste">TESTE</a></li>-->
            </ul>

        </div>

        <div class="col-md-4">

            <div class="btn-toolbar navbar-form navbar-right" role="toolbar" aria-label="...">
                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn active" id="countdowndiv">
                        <span class="glyphicon glyphicon-hourglass" id="clock"></span>
                    </button>  
                </div>

                <div class="btn-group" role="group" aria-label="...">
                    <button type="button" class="btn btn-info active">
                        <span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['log']['Usuario']; ?> 
                    </button>  
                </div>

                <div class="btn-group" role="group" aria-label="...">
                    <a href="<?php echo base_url(); ?>login/sair">
                        <button type="button" class="btn btn-danger">
                            <span class="glyphicon glyphicon-log-out"></span> Sair
                        </button>
                    </a> 
                </div>                                

                <div class="btn-group" role="group" aria-label="..."> </div>  

            </div>           
            
        </div>          
        

    </div>

</nav>

<br>