function getCurrentTime(){
    return moment().format('h:mm A');
}

function  getCurrentDateTime(){
    return moment().format('MM/DD/YY h:mm A');

}

function dateFormat(datetime){
    return moment(datetime,'YY-MM-DD HH:mm:ss').format('MM/DD/YY h:mm A');

}

function timeFormat(datetime){
    return moment(datetime, "YY-MM-DD HH:mm:ss").format('h:mm A');
    

}