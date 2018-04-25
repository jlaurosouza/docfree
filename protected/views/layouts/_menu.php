<ul>
    <li id="default">
        <a href="<?= Yii::app()->createAbsoluteUrl('/main/default/index') ?>" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent"> Inicio</span></a>
    </li>            
    <li>
        <a href="#" class="a-cadastros"><i class="fa fa-lg fa-fw fa-wrench"></i> <span class="menu-item-parent"> Serviços</span></a>
        <ul>
            <li id="indexacao">
                <a href="<?= Yii::app()->createAbsoluteUrl('/servico/indexacao/create') ?>"><i class="fa fa-fw fa-clipboard"></i> Novo Documento</a>
            </li>
            <li id="pesquisar">
                <a href="<?= Yii::app()->createAbsoluteUrl('/servico/pesquisar/index') ?>"><i class="fa fa-fw fa-search"></i> Pesquisar Documento</a>
            </li>
            <!--<li>
                <a href="#">Mudança de Departamento</a>
            </li>-->           
        </ul>
    </li>   
<!--    <li class="">
        <a href="#" class="a-associado"><i class="fa fa-lg fa-fw fa-suitcase"></i> <span class="menu-itm-parent"> Associados</span></a>
        <ul>
            <li id="associados">
                <a href="<-?= Yii::app()->createAbsoluteUrl('/main/associados/index') ?>" class="current"><i class="fa fa-fw fa-suitcase"></i> &nbsp;Novo Associado</a>
            </li>
            <li id="associadosfuncionario">
                <a href="<-?= Yii::app()->createAbsoluteUrl('/main/associadosfuncionario/index') ?>" class="current"><i class="fa fa-fw fa-group"></i> &nbsp;Novo Funcionário</a>
            </li>
        </ul>
    </li>-->
    <li>       
        <a href="#" class="a-usuarios"><i class="fa fa-lg fa-fw fa-unlock"></i> <span class="menu-item-parent">Acesso ao Sistema</span></a>
        <ul>
            <li id="usuarios">
                <a href="<?= Yii::app()->createAbsoluteUrl('/main/usuarios/index') ?>"><i class="fa fa-fw fa-user"></i> &nbsp; Usuários</a>
            </li>
            <!-- <li id="usuariosrd">
                 <a href="<-?= Yii::app()->createAbsoluteUrl('/main/usuarios/redefinirsenha/') ?>"><i class="fa fa-fw fa-key"></i> &nbsp; Redefinir Senha</a>                          
             </li>
             <li id="nivelacesso">
                 <a href="<-?= Yii::app()->createAbsoluteUrl('/main/nivelacesso/index/') ?>"><i class="fa fa-fw fa-sort-amount-asc "></i> &nbsp; Nível de Acesso</a>                          
             </li>
             <li id="controleacesso">
                 <a href="<-?= Yii::app()->createAbsoluteUrl('/main/controleacesso/index/') ?>"><i class="fa fa-fw fa-tasks"></i> &nbsp; Controle de Acesso</a>                          
             </li>-->
        </ul>
    </li>
    <li>
        <a href="#" class="a-gerencia"><i class="fa fa-lg fa-fw fa-cog"></i> <span class="menu-item-parent"> Gerenciamento</span></a>
        <ul>
            <li id="departamento">
                <a href="<?= Yii::app()->createAbsoluteUrl('/main/departamento/index') ?>"><i class="fa fa-fw fa-archive"></i>&nbsp;Departamentos</a>
            </li>
            <li id="tipodocumento">
                <a href="<?= Yii::app()->createAbsoluteUrl('/main/tipodocumento/index') ?>"><i class="fa fa-fw fa-file-text "></i>&nbsp;Tipos Documentais</a>
            </li>
            <li id="integridade">
                <a href="<?= Yii::app()->createAbsoluteUrl('/main/integridade/index') ?>"><i class="fa fa-fw fa-link "></i>&nbsp;Integração Gerêncial</a>
            </li>
            <li id="customizacao">
                <a href="<?= Yii::app()->createAbsoluteUrl('/main/customizacao/create') ?>"><i class="fa fa-fw fa-puzzle-piece "></i>&nbsp;Customização</a>
            </li>
        </ul>
    </li>
    <!-- <li class="">
         <a href="#" id="MENU-RELATORIO"><i class="icon-list-alt"></i>Relatórios<span id="MENU-RELATORIO" id="MENU-RELATORIO" class="badge"><span id="MENU-RELATORIO" id="MENU-RELATORIO" class="MENU-RELATORIO icon-chevron-down"></span></span></a>
         <ul>
             <li>
                 <a href="#">Quantitativo</a>
             </li>
             <li>
                 <a href="#">Documental</a>
             </li>
         </ul>
     </li> -->
</ul>
<br>
<span>
    <center>
    <!--    <img alt="Docfree" src="/docfree/assets/img/logo.png"> -->
    </center>
</span>
</ul>
