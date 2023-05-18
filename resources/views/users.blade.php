@extends('app')

@section('lpagename')
    Users
@endsection

@section('rpagename')
    User Management > Users
@endsection

@section('content')
<div class="row">
    @if(session()->get('role') == "administrator")
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody id="displayUsers">
                    @foreach($users as $row)
                        <tr id="showModal" data-type="update" data-userid="{{ $row['userid'] }}">
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['email'] }}</td>
                            <td>{{ $row['role'] }}</td>
                            <td>{{ date('Y-m-d', strtotime($row['created_at'])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button class="btn btn-sm btn-primary" style="float: right" id="showModal" data-type="add">
            Add User
        </button>
    </div>
    @else
        <div class="col-md-12">
            <h5>
                You dont have access here.
            </h5>
        </div>
    @endif

</div>

<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="roleModalLabel">Modal title</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
                <label for="">Email Address</label>
                <input type="text" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="">Role</label>
                <select id="role" class="form-control">
                    @foreach($roles as $row)
                        <option value="{{ $row['roleid'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="deleteUser">Delete</button>
            <button type="button" class="btn btn-primary" id="addUser">Save</button>
            <button type="button" class="btn btn-primary" id="updateUser">Update</button>
            <button type="button" class="btn btn-secondary" id="showModal" data-type="close">Cancel</button>
        </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        $(document).on('click','#showModal',function(e){
            e.preventDefault()
            type = $(this).data('type')
            if(type == "add"){
                $('#roleModalLabel').text('Add User')
                $('#roleModal').modal('show')
                $('#addUser').show('fade');
                $('#updateUser').hide('fade');
                $('#deleteUser').hide('fade');
                $('#name').val("")
                $('#email').val("")
            } else if(type == "update"){
                $('#roleModalLabel').text('Update Role')
                $('#roleModal').modal('show')
                $('#addUser').hide('fade');
                $('#updateUser').show('fade');
                $('#deleteUser').show('fade');

                $.post('/admin/getUserInfo',{
                    userid: $(this).data('userid')
                }, function(data){
                   $('.modal-body').html("")
                   $('.modal-body').append(data.output)
                },'json');

            } else if(type == "close"){
                $('#roleModal').modal('hide')
            }
        });

        $('#updateUser').click(function(){
            $.post('/admin/processUser',{
                name: $('#name').val(),
                email: $('#email').val(),
                roleid: $('#role').val(),
                userid: $('#userid').val(),
                type: "update"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showUsers()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#addUser').click(function(){
            $.post('/admin/processUser',{
                name: $('#name').val(),
                email: $('#email').val(),
                roleid: $('#role').val(),
                type: "add"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showUsers()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#deleteUser').click(function(){
            $.post('/admin/processUser',{
                userid: $('#userid').val(),
                roleid: $('#role').val(),
                type: "delete"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showUsers()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        function showUsers(){
            $('#displayUsers').html("")
            $.post('/admin/fetchUsers', function(data){
                $('#displayUsers').append(data)
            });
        }

    });
</script>
@endsection