@extends('app')

@section('lpagename')
    Expense Categories
@endsection

@section('rpagename')
    Expense Management > Expense Categories
@endsection

@section('content')
<div class="row">
    @if(session()->get('role') == "administrator")
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Display Name</th>
                            <th>Desciption</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody id="displayCategory">
                        @foreach($data as $row)
                            <tr id="showModal" data-type="update" data-categoryid="{{ $row['categoryid'] }}">
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['desc'] }}</td>
                                <td>{{ date('Y-m-d', strtotime($row['created_at'])) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button class="btn btn-sm btn-primary" style="float: right" id="showModal" data-type="add">
                Add Category
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
                <label for="">Display Name</label>
                <input type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
                <label for="">Desciption</label>
                <input type="text" class="form-control" id="description">
            </div>
            <input type="hidden" id="categoryid">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="deleteRole">Delete</button>
            <button type="button" class="btn btn-primary" id="addRole">Save</button>
            <button type="button" class="btn btn-primary" id="updateRole">Update</button>
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
            $("#name").val("")
            $("#description").val("")
            if(type == "add"){
                $('#roleModalLabel').text('Add Role')
                $('#roleModal').modal('show')
                $('#addRole').show('fade');
                $('#updateRole').hide('fade');
                $('#deleteRole').hide('fade');
            } else if(type == "update"){
                $('#roleModalLabel').text('Update Role')
                $('#roleModal').modal('show')
                $('#addRole').hide('fade');
                
                $.post('/admin/getCategoryInfo',{
                    categoryid: $(this).data('categoryid')
                }, function(data){
                    $("#name").val(data.name)
                    $("#description").val(data.desc)
                    $("#categoryid").val(data.categoryid)
                }, 'json');

                $('#updateRole').show('fade');
                $('#deleteRole').show('fade');

            } else if(type == "close"){
                $('#roleModal').modal('hide')
            }
        });

        $('#updateRole').click(function(){
            $.post('/admin/processCategory',{
                name: $('#name').val(),
                description: $('#description').val(),
                categoryid: $('#categoryid').val(),
                type: "update"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showCategory()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#addRole').click(function(){
            $.post('/admin/processCategory',{
                name: $('#name').val(),
                description: $('#description').val(),
                type: "add"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showCategory()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#deleteRole').click(function(){
            $.post('/admin/processCategory',{
                categoryid: $('#categoryid').val(),
                type: "delete"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showCategory()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        function showCategory(){
            $('#displayCategory').html("")
            $.post('/admin/fetchCategory', function(data){
                $('#displayCategory').append(data)
            });
        }

    });
</script>
@endsection