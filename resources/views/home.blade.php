@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="users">
            <h5>Available Users</h5>
            <ul class ="list-group list-chat-item">
            @if($users->count())
                @foreach($users as $user)
                    <li class ="chat-user-list">
                    <a href="{{route('message.conversation',$user->id)}}">
                        <div class ="chat-image">
                                {!!makeImageFromName($user->name)!!}
                            <i class="fa fa-circle user-status-icon user-icon-{{$user->id}}" title="away"></i>
                        </div>
                        <div class="Chat-name font-weight-bold">
                         {{$user->name}}
                        </div>
                    </a>
                    </li>
                 @endforeach   
            @endif

            
            </ul>
        </div>
    </div>
    

    <div class ="info" style ="position:absolute; right:50px;height:100%; width:75%; top:75px;  background-color:white">
        <h1 style="position:absolute; top:200px; right:300px;">Message Section</h1>
        <p class="lead" style ="position:absolute; right:250px; top:250px;">
            Select User From the List To Begin Conversation
        </p>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){

            let user_id ="{{auth()->user()->id}}"; 

            let ip_address = '127.0.0.1';
            let socket_port  =  '3000';

            let socket =io(ip_address + ':' + socket_port);
            

             socket.on('connect', function(){
                socket.emit( "user_connected", user_id );
             });

             socket.on('updateUserStatus',(data)=>{
                 let $userStatusIcon = $('.user-status-icon');
                 $userStatusIcon.removeClass('text-success');
                 $userStatusIcon.attr('title','Away');

                 $.each(data, function(key,val){
                     if(val !==null && val !== 0){
                         let $userIcon =$(".user-icon-" +key);
                         $userIcon.addClass('text-success');
                         $userIcon.attr('title','online');


                     }
                 });
             });




        });

    </script>
@endpush