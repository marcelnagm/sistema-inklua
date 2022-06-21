<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('') }}/admin">
        <div class="sidebar-brand-text mx-3">Inklua</div>
    </a>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Menu
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="/admin/usuarios/{{$logged->id}}/edit">
            <i class="fas fa-fw fa-users"></i>
            <span>Conta</span>
        </a>
    </li>
    
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#article"
            aria-expanded="true" aria-controls="article">
            <i class="far fa-newspaper"></i>
            <span>Conteúdos externos</span>
        </a>
        <div id="article" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Conteúdos externos:</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/artigos">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/artigos/create">Criar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#ad"
            aria-expanded="true" aria-controls="ad">
            <i class="fas fa-ad"></i>
            <span>Anúncios</span>
        </a>
        <div id="ad" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Anúncios:</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/anuncios">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/anuncios/create">Criar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#group"
            aria-expanded="true" aria-controls="group">
            <i class="fas fa-fw fa-layer-group"></i>
            <span>Grupos de vagas</span>
        </a>
        <div id="group" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas/create">Agrupar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#internal-positions"
            aria-expanded="true" aria-controls="group">
            <i class="fas fa-fw fa-users"></i>
            <span>Vagas Internas</span>
        </a>
        <div id="internal-positions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('') }}/admin/vagas">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/vagas?status=aguardando_aprovacao">Aprovar</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#external-positions"
            aria-expanded="true" aria-controls="group">
            <i class="fas fa-fw fa-users"></i>
            <span>Vagas Externas</span>
        </a>
        <div id="external-positions" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('') }}/admin/usuarios/vagas">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/usuarios/vagas?status=aguardando_aprovacao">Aprovar</a>
            </div>
        </div>
    </li>

      <!--Nav Item - Pages Collapse Menu--> 
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#loc"
            aria-expanded="true" aria-controls="report">
            <i class="fas fa-clipboard"></i>
            <span>Localização</span>
        </a>
        <div id="loc" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Localização:</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/states">Estados</a>
                <a class="collapse-item" href="{{ url('') }}/admin/citys">Cidades</a>
            </div>
        </div>
    </li>
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#tech"
            aria-expanded="true" aria-controls="report">
            <i class="fas fa-clipboard"></i>
            <span>Mapeamento Tech</span>
        </a>
        <div id="tech" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Mapeamento Tech:</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/candidate">Candidatos</a>
                <a class="collapse-item" href="{{ url('') }}/admin/role">Funções</a>
                <a class="collapse-item" href="{{ url('') }}/admin/english_level">Nível de Inglês</a>
                <a class="collapse-item" href="{{ url('') }}/admin/status">Status</a>
            </div>
        </div>
    </li>
    
    @if(env('APP_ENV') == 'dev' )
    
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#hunt"
            aria-expanded="true" aria-controls="hunt">
            <i class="fas fa-clipboard"></i>
            <span>Sistema Hunting</span>
        </a>
        <div id="hunt" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Administrador :</h6>
                <a class="collapse-item" href="{{ route('hunt.index') }}">Candidatos</a>
                <a class="collapse-item" href="{{ route('users.index') }}">Recrutadores</a>
            </div>
        </div>
    </li>
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#carteira"
            aria-expanded="true" aria-controls="carteura">
            <i class="fas fa-clipboard"></i>
            <span>Carteira</span>
        </a>
        <div id="carteira" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Administrador :</h6>
                <a class="collapse-item" href="{{ route('inklua_office.index') }}">Escritórios</a>
                <a class="collapse-item" href="{{ route('clients.index') }}">Clientes</a>             
            </div>
        </div>
    </li>
    @endif
     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#report"
            aria-expanded="true" aria-controls="report">
            <i class="fas fa-clipboard"></i>
            <span>Relatórios</span>
        </a>
        <div id="report" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Relatórios:</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/report/inkoins">Inkoins geradas</a>
                <a class="collapse-item" href="{{ url('') }}/admin/report/inkoins/donation">Inkoins doadas</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#config"
            aria-expanded="true" aria-controls="group">
            <i class="fas fa-fw fa-cog"></i>
            <span>Configurações</span>
        </a>
        <div id="config" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('') }}/admin/vagas/importar">Importação manual</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->