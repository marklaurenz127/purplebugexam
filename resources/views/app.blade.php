<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <style>
    .sidebar {
      background-color: #134F5C;
      color: #fff;
      height: 100vh;
      margin-left: 0;
      padding-left: 0;
      padding-top: 50px;
    }

    .sidebar .nav-link {
      color: #fff;
    }

    .sidebar .nav-link.active {
      font-weight: bold;
    }

    .side-main{
        padding-left: 0px;
    }

    .upper-header{
        padding: 20px;

        display: flex;
        justify-content: flex-end;
    }

    .upper-header a{
        text-decoration: none;
        color: black;
        margin-right: 20px;
    }

    .upper-header:after{
        border-bottom: 2px solid black;
    }

    .space-between{
        display: flex;
        justify-content: space-between;
    }

    li{
        list-style: none;
    }

    li a{
        color: white;
        text-decoration: none;
    }

    li a:hover{
        color: white;
        text-decoration: none;
    }

    .profile{
        margin-left: 20px;
    }

    .picture{
        width: 70px;
        height: 70px;
        background-color: #CBCBCB;
        border-radius: 50px;
    }

  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-2 side-main">
        <div class="sidebar">
            <div class="profile">
                <div class="picture">

                </div>
            </div>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="/">
                @if(!empty(session()->get('usersession')))
                    {{ session()->get('name') }} ({{ session()->get('role') }})
                @else
                    Dashboard
                @endif
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/">
                Home
              </a>
            </li>
            <li class="nav-item"> <a class="nav-link" href="javascript:void(0)">User Management</a>
                <ul>
                    <li><a href="/roles">Roles</a></li>
                    <li><a href="/users">Users</a></li>
                </ul>
            </li>
            <li class="nav-item"> <a class="nav-link" href="javascript:void(0)">Expense Management</a>
                <ul>
                    <li><a href="/expense categories">Expense Categories</a></li>
                    <li><a href="/expenses">Expenses</a></li>
                </ul>
            </li>
            @if(session()->get('role') == "user")
            <li class="nav-item"> <a class="nav-link" href="javascript:void(0)">User</a>
                <ul>
                    <li><a href="/change password">Change password</a></li>
                </ul>
            </li>
            @endif
          </ul>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-md-10">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="upper-header">
                    <a href="javascript:void(0)">Welcome to Expense Manager</a>
                    <a href="/logout">Logout</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header space-between">
                        <h4 class="card-title">
                            @yield('lpagename')
                        </h4>
                        <h4 class="card-title">
                            @yield('rpagename')
                        </h4>
                    </div>
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <!-- Add your content here -->
      </div>
    </div>
  </div>
  <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> -->
  <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
  </script>
  @yield('js')
</body>
</html>
