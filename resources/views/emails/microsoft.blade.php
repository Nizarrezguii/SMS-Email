<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mailbox</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('backend/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('backend/dist/css/adminlte.min.css')}}">
    <!-- Livewire CSS -->
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
    <!-- Navbar -->
    @include('backend.layouts.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('backend.layouts.sidebar')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inbox</h1>
                        <button class="btn btn-primary mt-3" type="button" onclick="window.location.href='{{ route('fetch.latest.email') }}'">Fetch Latest Email</button>

                        {{-- <button class="btn btn-primary ms-3 mt-3">compose</button> --}}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home fs</a></li>
                        <li class="breadcrumb-item active">Inbox</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title mt-3 ">Inbox</h3>
                        <div class="card-tools mt-3">
                            <div class="input-group input-group-sm">
                                <form method="GET" action="{{ route('emails.index') }}">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" class="form-control" placeholder="Search by subject" value="{{ $search }}">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </form>
                    </div>
                        </div>
                    <!-- /.card-tools -->
                        </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>From</th>
                                    <th>subject</th>
                                    <th>Body</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($emails as $email)
                            <tr>
                                <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                                <td class="mailbox-name text-primary">{{ $email->from }}</td>
                                <td class="mailbox-subject">{{ $email->subject }}</td>
                                <td class="mailbox-body">{{ $email->body }}</td>
                                <td class="mailbox-date">{{ $email->date }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer p-0">
                    <div class="mailbox-controls">
                    <!-- Check all button -->
                    </div>
                        <!-- /.btn-group -->
                        <div class="float-right">
                            {{ $emails->links() }}
                        <!-- /.btn-group -->
                    </div>
                    <!-- /.float-right -->
                </div>
                </div>
            </div>
            <!-- /.card -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
            </div>
        <!-- /.content -->
    </div>
</div>
    <!-- Include Bootstrap JavaScript -->
    <!-- jQuery -->
    <script src="{{asset('backend/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('backend/dist/js/adminlte.min.js')}}"></script>
    <!-- Page specific script -->
    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
