@extends('app')

@section('lpagename')
    Change password
@endsection

@section('rpagename')
    User > Change password
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="">Enter new password</label>
            <input type="password" id="password" class="form-control">
        </div>
        <br>
        <button class="form-control btn btn-info" id="changePass">
            Save
        </button>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){

        $('#changePass').click(function(){
            $.post('/user/changePassword',{
                password: $('#password').val()
            }, function(data){
                if(data.status){ 
                    alert(data.msg)
                    location.reload()
                }else{
                    alert(data.msg)
                }
            }, 'json');
        });

    });
</script>
@endsection