@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('/vendor/piechart/c3.css') }}">
@endsection

@section('lpagename')
    My Expenses
@endsection

@section('rpagename')
    Dashboard
@endsection

@section('content')
<div class="row">
    @if(empty(session()->get('usersession')))
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Enter email</label>
            <input type="text" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="">Enter password <small><i>Default password is 0000</i></small> </label>
            <input type="password" class="form-control" id="password">
        </div><br>
        <button class="form-control btn btn-info" id="btnLogin">
            Login
        </button>
    </div>
    @else
        <div class="col-md-6">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Expense Categories</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td>{{ $row['category'] }}</td>
                            <td>&#36; {{ number_format($row['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div id="c3chart_category" style="height: 420px;"></div>
        </div>
    @endif
</div>
@endsection

@section('js')
<script src="{{ asset('/vendor/piechart/c3.min.js') }}"></script>
<script src="{{ asset('/vendor/piechart/d3-5.4.0.min.js') }}"></script>
<script src="{{ asset('/vendor/piechart/C3chartjs.js') }}"></script>
<script>
    $(document).ready(function(){

        var dataArray = [];
        getDate();
        function getDate(){
            $.post('/admin/getAllExpenses', function(data) {
                data.forEach(function(item) {
                    var newItem = [
                        item.category,
                        item.total
                    ];
                    dataArray.push(newItem);
                });
            });
            
        }

        setTimeout(() => {
            displayChart();
        }, 1000);

        function displayChart(){
            var chart = c3.generate({
                bindto: "#c3chart_category",
                data: {
                    columns: dataArray,
                    type: 'donut',
                    onclick: function(d, i) { console.log("onclick", d, i); },
                    onmouseover: function(d, i) { console.log("onmouseover", d, i); },
                    onmouseout: function(d, i) { console.log("onmouseout", d, i); },
                },
                donut: {
                    label: {
                        show: false
                    }
                },
            });
        }

        $('#btnLogin').click(function(){
            $.post('/login',{
                email: $('#email').val(),
                password: $('#password').val()
            }, function(data){
                if(data.status){
                    location.reload();
                }else{
                    alert(data.msg)
                }
            },'json');
        });


    });
</script>
@endsection