$(document).ready(function(){
  $('.lastmonths').change(function(){
    var Rdatee = $(this).find('option:selected').text();
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
      }
  });
  $.ajax({
      method: "GET",
      url:"/admin/orders/get",
      data:{
          "month": Rdatee
          },
      success:function(res){
        console.log(res);
        $('#productstable').dataTable().fnDestroy();
      }
  })
  })
    ////for toast
    function launch_toast() {
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    }

    var origion  = window.location.origin  
    ////to show all products in dataTable
    $('#productstable').DataTable({
       dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "pageLength": 50,
        "order": [[ 0, 'desc' ]],
        "pagingType": "full_numbers",
        processing: true,
        serverSide: true,
        oLanguage: {sProcessing: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'},
        ajax: origion+'/admin/orders/get', 
        columns: [
          {data: 'odate',name: 'odate','render':function(data, type, row){
            var myObj = $.parseJSON('{"date_created":"'+data+'"}'),
            myDate = new Date(1000*myObj.date_created);
            var vdatee  = myDate.toLocaleString(); 
            return data;
          }},
          {data: 'items',name: 'items', orderable: false, "render" : function ( data, type, row) {
            var output="<ul>";
            for(var i=0;i<data.length;i++){
              output +=  "<li>"+data[i].name+"</li>" ; 
            
            }
            output +="</ul>"
            return output;
        }},
        {data: 'items',name: 'items', orderable: false, "render" : function ( data, type, row) {
          var output="0";
          for(var i=0;i<data.length;i++){
            output =parseInt(output)+parseInt(data[i].quantity) ;
          }
          return output;
      }},
  
            {data: 'email', name: 'email'}, 
          
            {data: 'items',name: 'items', orderable: false, "render" : function ( data, type, row) {
              // var checktest="0";
              // for(var i=0;i<data.length;i++){   //// getting total price without tax and other things
              //   checktest = parseFloat(checktest)+parseFloat(data[i].price)*parseFloat(data[i].quantity);
              // }
              //   var gain =  parseFloat(row['cost']) - parseFloat(checktest); 
              //   gain = gain.toFixed(2);
              //   gain = parseFloat(gain) / parseFloat(row['quantity']); 
              //   gain = gain.toFixed(2);     ///// per product tax 
                // return row['difperitem'];
                var output="0";
                var cost = "0";
                // var totalless = parseFloat(row['subtotal'])*parseFloat(0.974)-parseFloat(0.30);
                // totalless =  parseFloat(row['subtotal']) -parseFloat(totalless);
                // if(row['quantity'] == 0){
                //   row['quantity'] == 1;
                // }
                // totalless = parseFloat(totalless) / parseFloat(row['quantity']);
                // totalless = totalless.toFixed(2);
                for(var i=0;i<data.length;i++){
                  output =parseFloat(output)+parseFloat(data[i].price)*parseFloat(data[i].quantity)+parseFloat(row['difperitem']) ;
                  cost =parseFloat(cost)+parseFloat(data[i].retail_price)*parseFloat(data[i].quantity)+parseFloat(row['costdiffper']) ;
                } 
                // return row['costdiffper'];
                // var percost = parseFloat(row['cost'])- parseFloat(cost); 
                output = output.toFixed(2);   //// getting cost of all items
                var poutput = parseFloat(cost) * parseFloat(0.97) - parseFloat(0.30)-parseFloat(output);    /// jason farmoula
                if(row['profitper'] !== null ){
                poutput = poutput*row['profitper'];
                return '$'+poutput.toFixed(2)+' '+row['order_id'];
              }
              else{
                return "Profit Not Set";
              }
            }}, 
        ],
    });   

    ////to show all profit in dataTable
    $('#profittable').DataTable({
      dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print' 
        ],
      "pageLength": 50,
      "order": [[ 1, 'desc' ]],
      "pagingType": "full_numbers",
      processing: true,
      serverSide: true,
      oLanguage: {sProcessing: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'},
      ajax: origion+'/admin/getprofit/get', 
      columns: [

          {data: 'client_id', name: 'client_id'}, 
          {data: 'month', name: 'month','render':function(data, type, row){
            data = data.split('-');
            data = data[0]+'-'+data[1];
            return data;
          }},
          {data: 'profit', name: 'profit'}, 
       
      ],
  });   

    function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
  
          reader.onload = function (e) {
              $('.dumyimg').attr('src', e.target.result);
          }
  
          reader.readAsDataURL(input.files[0]);
      }
  }
  
  $("[name='image']").change(function(){
      readURL(this);
  });


            // settings of toats
    // iziToast.settings({
    //     timeout: 3000, // default timeout
    //     resetOnHover: true,
    //     // icon: '', // icon class
    //     transitionIn: 'flipInX',
    //     transitionOut: 'flipOutX',
    //     position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
    //     onOpen: function () {
    //       console.log('callback abriu!');
    //     },
    //     onClose: function () {
    //       console.log("callback fechou!");
    //     }
    //   });




});
