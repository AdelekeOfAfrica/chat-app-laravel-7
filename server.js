const express= require('express');
const app = express();

const server =require('http').createServer(app);

const io = require('socket.io')(server,{
    cors:{origin:"*"}
} );

const Redis = require ('ioredis');
const redis = new Redis();

const users = [];
const groups =[];

redis.subscribe('private-channel', function (){
   console.log('subscribed to a private channel '); 
});

redis.on('message ', function(channel, message) {
    // message = JSON.parse(message);
    // console.log(channel );
    // console.log(message);
    // if(channel == 'private-channel'){
    //     let data = message.data.data;
    //     let receiver_id =data.receiver_id;
    //     let event = message.event();

    //     io.socket(`${users[receiver_id]}`).emit(channel + ':' + message.event, data);
    io.socket.emit('message',message); 

    // }
});





io.on('connection',(socket)=>{
    socket.on("user_connected", function(user_id){
        users[user_id] = socket.id;
        io.emit('updateUserStatus',users);
         console.log("user connected" + user_id);
        });
        
        
    socket.on('sendChatToServer',function(message){
        console.log(message);

        socket.broadcast.emit('sendChatToClient',message);
        //io.sockets.emit('sendChatToClient',message);
    }); //new code testing 
        

        socket.on('disconnect', function() {
            const i = users.indexOf(socket.id);
            users.splice(i, 1, 0 );
            io.emit('updateUserStatus', users);
            console.log(users);
            

        });
        socket.on('joinGroup',function(data){
            console.log(data);
            data['socket_id'] = socket.id;
            if(groups[data.group_id]){
                var userExist =checkIfUserExistInGroup(data.user_id, data.group_id);
            }
            else{

            }
        })

        function checkIfUserExistInGroup(){
            var group = groups[group_id];
            var exist = flase;
            if(group.length>0){
              for(var i=0; i<group.length; i++) {
                  if(group[i]['user_id'] == user_id){
                      exist = true;
                      break;

                  }
              }  
            }
        }


});
  

server.listen(3000, () => {
console.log('server has started ');
});



