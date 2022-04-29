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
            <i class="fas fa-fw fa-users"></i>
            <span>Vagas</span>
        </a>
        <div id="group" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ url('') }}/admin/vagas">Gerenciar</a>
                <h6 class="collapse-header">Agrupamentos</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas/create">Agrupar</a>
            </div>
        </div>
        <div id="group" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Importação manual</h6>
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas">Gerenciar</a>
                <a class="collapse-item" href="{{ url('') }}/admin/grupo/vagas/create">Agrupar</a>
            </div>
        </div>
    </li>

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