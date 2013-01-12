
function sum(){
 var one = eval(document.log.fl_time.value*1);
 window.document.log.total_time.value = one;

 var two = eval(document.log.sl_time.value*1);
 window.document.log.total_time.value = two;
    
 var three = eval(document.log.tl_time.value*1)
 window.document.log.total_time.value = three;
      
 var total = one + two + three;
 window.document.log.total_time.value = total;
}

