@extends('app')

@section('lpagename')
    Expenses
@endsection

@section('rpagename')
    Expense Management > Expenses
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Expense Category</th>
                        <th>Amount</th>
                        <th>Entry Date</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody id="displayExpenses">
                    @if(count($data) > 0)
                        @foreach($data as $row)
                            <tr id="showModal" data-type="update" data-expenseid="{{ $row['expenseid'] }}">
                                <td>{{ $row['name'] }}</td>
                                <td>&#36; {{ number_format($row['amount'], 2) }}</td>
                                <td>{{ date('Y-m-d', strtotime($row['entrydate'])) }}</td>
                                <td>{{ date('Y-m-d', strtotime($row['created_at'])) }}</td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan="4">No expenses found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <button class="btn btn-sm btn-primary" style="float: right" id="showModal" data-type="add">
            Add Expense
        </button>
    </div>
</div>

<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="roleModalLabel">Modal title</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Expense Category</label>
                <select id="category" class="form-control">
                    <option value="-"> -- Select -- </option>
                    @foreach($categories as $row)
                        <option value="{{ $row['categoryid'] }}">{{ $row['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Amount</label>
                <input type="number" class="form-control" id="amount">
            </div>
            <div class="form-group">
                <label for="">Entry Date</label>
                <input type="date" class="form-control" id="entrydate">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="deleteExpense">Delete</button>
            <button type="button" class="btn btn-primary" id="addExpense">Save</button>
            <button type="button" class="btn btn-primary" id="updateExpense">Update</button>
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
            $("#amount").val("")
            $("#entrydate").val("")
            if(type == "add"){
                $('#roleModalLabel').text('Add Role')
                $('#roleModal').modal('show')
                $('#addExpense').show('fade');
                $('#updateExpense').hide('fade');
                $('#deleteExpense').hide('fade');
            } else if(type == "update"){
                $('#roleModalLabel').text('Update Role')
                $('#roleModal').modal('show')
                $('#addExpense').hide('fade');

                $.post('/admin/getExpenseInfo',{
                    expenseid: $(this).data('expenseid')
                }, function(data){
                    $('.modal-body').html("")
                    $('.modal-body').html(data)
                });

                $('#updateExpense').show('fade');
                $('#deleteExpense').show('fade');

            } else if(type == "close"){
                $('#roleModal').modal('hide')
            }
        });

        $('#updateExpense').click(function(){
            $.post('/admin/processExpense',{
                categoryid: $('#category').val(),
                amount: $('#amount').val(),
                entrydate: $('#entrydate').val(),
                expenseid: $('#expid').val(),
                type: "update"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showExpense()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#addExpense').click(function(){
            $.post('/admin/processExpense',{
                categoryid: $('#category').val(),
                amount: $('#amount').val(),
                entrydate: $('#entrydate').val(),
                type: "add"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showExpense()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        $('#deleteExpense').click(function(){
            $.post('/admin/processExpense',{
                expenseid: $('#expid').val(),
                type: "delete"
            },function(data){
                if(data.status){
                    alert(data.msg)
                    showExpense()
                    $('#roleModal').modal('hide');
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

        function showExpense(){
            $('#displayExpenses').html("")
            $.post('/admin/fetchExpenses', function(data){
                $('#displayExpenses').append(data)
            });
        }

    });
</script>
@endsection