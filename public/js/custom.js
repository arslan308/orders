$(document).ready(function(){
var origion  = window.location.origin  
window.getmonth = origion+'/admin/orders/get/current';
window.getprofit = origion+'/admin/getprofit/get/current';

$('.lastmonths').change(function(){
var Rdatee = $(this).find('option:selected').text();
var _path = origion+'/admin/orders/get/'+Rdatee;
window.getmonth = _path;
var _table = $('#productstable').DataTable();
_table.ajax.url(window.getmonth).load();
})

$('.monthprofit').change(function(){
var Rdatee = $(this).find('option:selected').text();
var _path = origion+'/admin/getprofit/get/'+Rdatee;
window.getprofit = _path;
var _table = $('#profittable').DataTable();
_table.ajax.url(window.getprofit).load();
})

////for toast
function launch_toast() {
  var x = document.getElementById("toast")
  x.className = "show";
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
}

// var table = $('#productstable').DataTable();

// $('[type="search"]').on( 'keyup', function () {
//   table
//       .columns( 3 )
//       .search( this.value )
//       .draw();
// });  
////to show all products in dataTable
$('#productstable').DataTable({
  dom: 'Bfrtip', 
  buttons: [
      'copy', 'csv', 'excel', 'pdf'
  ],
  "pageLength": 50,
  "order": [[ 0, 'desc' ]], 
  "pagingType": "full_numbers", 
  processing: true,
  // serverSide: true, 
  oLanguage: {sProcessing: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'},
  ajax: window.getmonth, 
  columns: [
        {data: 'odate',name: 'odate','render':function(data, type, row){
          var myObj = $.parseJSON('{"date_created":"'+data+'"}'),
          myDate = new Date(1000*myObj.date_created);
          var vdatee  = myDate.toLocaleString(); 
          return data;
        }},
        {data: 'order_id', name: 'order_id'},   
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
          var output="0";
          var cost = "0";
          for(var i=0;i<data.length;i++){
            output = parseFloat(output) + parseFloat(data[i].price)*parseFloat(data[i].quantity)+parseFloat(row['difperitem']) * parseFloat(data[i].quantity) ;
            cost = parseFloat(cost) + parseFloat(data[i].retail_price) * parseFloat(data[i].quantity) + parseFloat(row['costdiffper']) * parseFloat(data[i].quantity) -  parseFloat(row['cstmdiscount']) * parseFloat(data[i].quantity);
          } 
          output = parseFloat(output).toFixed(2);   //// getting cost of all items
          var poutput = parseFloat(cost) - parseFloat(output);    /// jason farmoula
          if(row['profitper'] !== null ){
          poutput = poutput*row['profitper'];   
          return '$'+poutput.toFixed(2);  
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
      'copy', 'csv', 'excel', 'pdf' 
  ],
"pageLength": 50,
"order": [[ 0, 'asc' ]],
"pagingType": "full_numbers",
processing: true,
serverSide: true,
oLanguage: {sProcessing: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'},
ajax: window.getprofit, 
columns: [

    {data: 'client_id', name: 'client_id'}, 
    {data: 'month', name: 'month','render':function(data, type, row){
      data = data.split('-');
      data = data[0]+'-'+data[1];
      return data;
    }},
    {data: 'profit', name: 'profit','render':function(data, type, row){
      return '$'+parseFloat(data).toFixed(2);
    }}, 
  
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

///edit in a popup
$('.edit, .name,.email,.type,.profit').click(function(){  
var _row = $(this).parents('tr');
var _name = $(_row).find('.name').text();
var _email = $(_row).find('.email').text();
var _id = $(_row).find('.id').text();
var _phone = $(_row).find('.phone').text();
var _profit = $(_row).find('.profit').text();
var _image = $(_row).find('.image').text();
var _type = $(_row).find('.type').text(); 
var _instahandle = $(_row).find('.instahandle').text(); 



$('.modal-body').find('[name="name"]').val(_name);
$('.modal-body').find('[name="email"]').val(_email);
$('.modal-body').find('[name="type"]').val(_type);
$('.modal-body').find('[name="phone"]').val(_phone);
$('.modal-body').find('[name="profit"]').val(_profit);
$('.modal-body').find('[name="id"]').val(_id);
$('.modal-body').find('[name="instahandle"]').val(_instahandle);  


if(_image == null){
$('.modal-body').find('.dumyimg').attr('src','https://via.placeholder.com/150');
}else{
$('.modal-body').find('.dumyimg').attr('src',_image);
}
$('.modal').modal('toggle');
})

////auto-login
$('.autologin').click(function(){
 var _email = $(this).parents('tr').find('.email').text(); 
 window.open("https://fanarchpartners.com/admin/vendors/autologin?email="+_email,      
 "_blank", "width=1500, height=1500");    

})
///delete by ajax

$('.delete').click(function(){
if (confirm("Do you want to delete")){
var _row = $(this).parents('tr');
var _id = $(_row).find('.id').text();
var _this = $(this);
$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
}
});
$.ajax({
method: "post",
url:"/admin/vendor/delete/"+_id,
data:{
    "id": _id
    },
success:function(res){
  console.log(res);
  _this.parents('tr').hide();
}
})
}
});

$('.announdelete').click(function(){
if (confirm("Do you want to delete")){
var _row = $(this).parents('tr');
var _id = $(_row).find('.id').text();
var _this = $(this);
$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
}
});
$.ajax({
method: "post",
url:"/admin/announce/delete/"+_id,
data:{
    "id": _id
    },
success:function(res){
  console.log(res);
  _this.parents('tr').hide();
}
})
}
});

var _handle = $('.handle').text();
$.ajax({    
url:"https://www.instagram.com/"+_handle+"/?__a=1", 
type: 'get',
success: function(res){
console.log(res['graphql']);
if(typeof(res['graphql']) != "undefined"){
$('.bio').text(res['graphql']['user']['biography'])
$('img.img-polaroid.img-responsive.img-circle').attr('src',res['graphql']['user']['profile_pic_url']);
$('span.followcount').text(res['graphql']['user']['edge_followed_by']['count']); 
$('span.chasecount').text(res['graphql']['user']['edge_follow']['count']); 
$('span.postcount').text(res['graphql']['user']['edge_owner_to_timeline_media']['count']); 
$('h1.profile-user-name').text(res['graphql']['user']['username']); 
$('.profile-user').css('opacity',1); 
} 
}
})  
$('.panel-collapse').on('show.bs.collapse', function () {
  $(this).siblings('.panel-heading').addClass('active');
});

$('.panel-collapse').on('hide.bs.collapse', function () { 
  $(this).siblings('.panel-heading').removeClass('active');
}); 

});
