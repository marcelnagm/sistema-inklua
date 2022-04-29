<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Custom fonts for this template -->
        <link href="/cms/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="/cms/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Datepicker -->
        <link rel="stylesheet" media="screen, print" href="{{ url("") }}/cms/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css">

       {{--  <!-- Multiselect -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
        
        <!-- Custom styles for this project -->
        <link href="/cms/css/custom.css" rel="stylesheet">

        <!-- Custom styles for this page -->
        <link href="/cms/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    </head>
    
    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">
        
            @include('cms.includes.sidebar')
        
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
        
            <!-- Main Content -->
            <div id="content">
        
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
        
                <!-- Sidebar Toggle (Topbar) -->
                <form class="form-inline">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                    </button>
                </form>
        

        
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
        
                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                    <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </li>
        

                    <div class="topbar-divider d-none d-sm-block"></div>
        
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{$logged->name}}</span>
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="/admin/usuarios/{{$logged->id}}/edit">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                        </a>
                    </div>
                    </li>
        
                </ul>
        
                </nav>
                <!-- End of Topbar -->

                <div class="container-fluid">

                @include('cms.includes.flash-message')

                @yield('content')
                
                </div>

            </div>
            <!-- End of Main Content -->
        
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Inklua {{date('Y')}}</span>
                </div>
                </div>
            </footer>
            <!-- End of Footer -->
        
            </div>
            <!-- End of Content Wrapper -->
        
        </div>
        <!-- End of Page Wrapper -->
        
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
        
        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body"> Você deseja encerrar sua sessão?</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Não, continuar usando</button>
                <form action="{{ route('logout') }}" method="post" id="form-loggout">
                    @csrf
                    <button type="submit" class="btn btn-primary">Sim, fazer logout</button>
                </form>
                </div>
            </div>
            </div>
        </div>

         <!-- Delete Modal-->
         <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remover</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body"> Tem certeza que deseja excluir o item <strong class="item-delete-title"></strong> ?</div>
                <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Não, cancelar</button>
                <form action="" method="post" id="delete-form">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger">Sim, remover</button>
                </form>
                </div>
            </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="/cms/vendor/jquery/jquery.min.js"></script>
        <script src="/cms/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="/cms/vendor/jquery-easing/jquery.easing.min.js"></script>
        
        <!-- Custom scripts for all pages-->
        <script src="/cms/js/sb-admin-2.js"></script>

        <!-- Page level plugins -->
        <script src="/cms/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="/cms/vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="/cms/js/demo/datatables-demo.js"></script>

        <!-- Datepicker -->
        <script src="{{ url("") }}/cms/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js"></script>

        <!-- Multiselect -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Custom Js -->
        <script src="{{ url("") }}/cms/js/admin.js"></script>


        <!-- Editor de conteúdo em textareas -->
        <script src="{{ url("") }}/tinymce/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: '#description',
                menubar: false,
                plugins: [
                    "advlist autolink link lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor fullscreen"
                ],
                toolbar: 'undo redo | insert | paste | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | fullscreen code',
                image_advtab: false,
                image_dimensions: false,
                // external_filemanager_path:"/tinymce/plugins/filemanager/",
                // filemanager_title:"Gerenciador de mídias" ,
                // external_plugins: { "filemanager" : "/tinymce/plugins/filemanager/plugin.min.js"},
                // filemanager_access_key:"nwdn39ufn93vn93vn3r0ivmi39m",
                paste_as_text: true
            });
        </script>
         <style>
            .mce-notification {display: none !important;}
        </style>
        <!------------------------------------------- -->

        

    </body>

</html>
