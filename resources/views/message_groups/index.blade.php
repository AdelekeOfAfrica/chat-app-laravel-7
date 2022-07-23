@extends('layouts.app')
<style>
  .select2-container{
      width:100% !important;
  }
</style>

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="users">
            <h5>Users</h5>
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


        <div class ="groups mt-5">
        <h5>Groups<i class="fa fa-plus btn-add-group ml-3"></i></h5>
        <ul class ="list-group list-chat-item">
         @if($groups->count())
            @foreach($groups as $group)
                <li class ="chat-user-list">
                <a href="{{route('message-group.show', $group->id)}}">
                {{$group->name}}
                </a>
                </li>
            @endforeach
            @endif
        </ul>
        </div>
    </div>
    





    <div class ="col-md-9 chat-section">
        <div class ="chat-header">
            <div class ="chat-image">
               {{$currentGroup->name}}
            </div>

            <div class="col-md-3">
            <h4>{{$currentGroup->name}}</h4>
            @if(isset($currentGroup->message_group_members) && !empty($currentGroup->messaage_group_members))
                <ul class="list-group">
                    @foreach($currentGroup->message_group_members as $member)
                    <li class ="list-group-item">
                     {!!makeImageFromName($member->user->name)!!}
                     {{$member->user->name}}
                    </li>

                    @endforeach
                </ul>
          
            @endif
            </div>

        </div>
   
        <div class="chat-body" id="chatBody">
            <div class="message-Listing" id="messageWrapper">
                <div class="row message align-items-center mb-2" >
                    

                    
                </div>

        </div>



            <div class="chat-box">
                <div class = "chat-input bg-white" id="chat-input" contenteditable="">

                </div>
                <div class = "chat-input-toolbar">
                    <button title="Add File " class ="btn btn-light btn-sm btn-file-upload">
                        <i class="fa fa-paperclip"></i>
                    </button>|
                    <button title ="Bold" class ="btn btn-light  btn-sm tool-items" onClick="document.execCommand('bold',false,'');">
                        <i class="fa fa-bold tool-icon"></i>
                    </button>|
                      <button title ="Italic" class ="btn btn-light  btn-sm tool-items"  onClick="document.execCommand('italic',false,'');">
                        <i class="fa fa-italic tool-icon"></i>
                    </button>
                </div>
            </div>
        </div>
</div>




<div class="modal" tabindex="-1" role="dialog" id="addgroupModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title">Add Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('message-group.store')}}" method="POST">
      {{csrf_field()}}
            <div class="modal-body">

                <div class="form-group">
                <label for="">Group Name</label>
                <input type ="text" class="form-control" name="name">
                </div>

                <div class="form-group">
                    <label for="">Select Users</label>
                    <select id="selectMember" name="user_id[]"  class="form-control"id="" multiple="multiple">
                    @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create Group</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
      </form>
    </div>
  </div>
</div>
@endsection 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(function(){
        let  $chatInput = $(".chat-input");
        let $chatInputToolbar = $(".chat-input-toolbar");
        let $chatBody = $(".chat-body");
        let $messageWrapper = $("#messageWrapper");


        let user_id ="{{auth()->user()->id}}"; 
        


        let ip_address = '127.0.0.1';
        let socket_port  =  '3000';

        let socket =io(ip_address + ':' + socket_port);
        let groupId = "{ !! $currentGroup->id !!}";
        let groupName = "{!! $currentGroup->name !!}"; 
        

            socket.on('connect', function(){
            let data ={group_id:groupId, user_id:user_id, room:"group" +groupId};
            socket.emit( "user_connected", user_id );
            socket.on('joinGroup',data);
            });

            // socket.on('updateUserStatus',(data)=>{
            //     let $userStatusIcon = $('.user-status-icon');
            //     $userStatusIcon.removeClass('text-success');
            //     $userStatusIcon.attr('title','Away');

            //     $.each(data, function(key,val){
            //         if(val !==null && val !== 0){
            //             let $userIcon =$(".user-icon-" +key);
            //             $userIcon.addClass('text-success');
            //             $userIcon.attr('title','online');


            //         }
            //     });
            // });

            $chatInput.keypress(function(e){
                let message = $(this).html();
                console.log(message);
                if(e.which === 13 && !e.shiftKey ){
                
                    $chatInput.html("");
                  
                    sendMessage(message);
                    return false;
                }
            });

            function sendMessage(message){
                let url = "{{route('message.send-group-message')}}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{csrf_token()}}";

                formData.append('message',message);
                formData.append('_token',token);
                formData.append('message_group_id',groupId);

                appendMessageToSender(message);
                socket.emit('sendChatToServer',message);

            $.ajax({
                url:url,
                type:'POST',
                data:formData,
                processData:false,
                contentType:false,
                dataType:'JSON',
                success:function(response){
                    if(response.success){
                        console.log(response.data);

                    }
                }
            });

            }
            
            function appendMessageToSender(message){
                let messageContent ='<div class="col-md-12 message-content">\n'+

                                        '<div class ="message-text row message align-items-center mb-2" style="width:100%;border-radius:5px; padding:10px; margin:10px 0; height:100%; background-color:#e6e6fa;">\n'+message+
                                        '</div>\n'+

                                    '</div>';

                let newMessage = '<div class ="row message align-items-center mb-2" >'
                               + messageContent+'</div>';

                $messageWrapper.append(newMessage);

             }


            function appendMessageToReceiver(message){
                let messageContent ='<div class="col-md-12 message-content">\n'+

                                        '<div class ="message-text row message align-items-center mb-2" style="width:100%;border-radius:5px; padding:10px; margin:10px 0; height:100%; background-color:#e6e8fa;">\n'+message+
                                        '</div>\n'+

                                    '</div>';

                let newMessage = '<div class ="row message align-items-center mb-2" >'
                                + messageContent+'</div>';

                $messageWrapper.append(newMessage);
                 

            }

            socket.on('sendChatToClient',(message)=>{
                
                appendMessageToReceiver(message);
            });


  
    $addgroupModal = $("#addgroupModal");
    $(document).on("click",".btn-add-group", function(){
        $addgroupModal.modal();
    });

    $("#selectMember").select2();
                
    });

        
</script>
@endpush
