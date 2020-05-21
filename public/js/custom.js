$(document).ready(function(){

    ////for toast
    function launch_toast() {
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    }

    var origion  = window.location.origin
    ////to show all products in dataTable
    $('#productstable').DataTable({
        "pagingType": "full_numbers",
        processing: true,
        serverSide: true,
        oLanguage: {sProcessing: '<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>'},
        ajax: origion+'/admin/orders/get', 
        columns: [
            {data: 'email', name: 'email'},
            {data: 'line_items',name: 'line_items',"render" : function ( data, type, row) {
                var output="0";
                for(var i=0;i<data.length;i++){
                  output =parseFloat(output)+parseFloat(data[i].price) ;
                }
                return '$'+output.toFixed(2);
            }},
            {data: 'created_at',name: 'created_at','render':function(data, type, row){
                var date = new Date(data);
                var $month =  parseInt(date.getMonth())+parseInt(1);
                return date.getFullYear() + '-' + $month + '-' + date.getDate();
            }},
            
            {data: 'line_items',name: 'line_items',"render" : function ( data, type, row) {
                var output="";
                for(var i=0;i<data.length;i++){
                  output +=  data[i].title ;
                  if(i< data.length-1){
                    output +="     ,     ";
                  }
                }
                return output;
            }},
            {data: 'line_items',name: 'line_items',"render" : function ( data, type, row) {
                var output="0";
                for(var i=0;i<data.length;i++){
                  output =parseInt(output)+parseInt(data[i].quantity) ;
                }
                return output;
            }},

        ]
    });


    ////disable when checkbox is checked
    $(document).on('click','[name="pdr_checkbox[]"]',function(){
        if($(this).is(':checked')){
        $(this).parents('tr').find('td:last-child button').prop('disabled',true);
        $('.multi-add-to-cart').css('opacity','1');
        }
        else{
        $(this).parents('tr').find('td:last-child button').prop('disabled',false);
        if($('td.select-checkbox input:checked').length == 0){
        $('.multi-add-to-cart').css('opacity','0');
        }
        }
        })

        /// on parent checkbox clicked , checked all checkboxes
        $('[name="parent"]').click(function(){
            if($(this).is(':checked')){
                $('td.select-checkbox input').prop('checked',true);
                $('tr').find('td:last-child button').prop('disabled',true);
                $('.multi-add-to-cart').css('opacity','1');

            }
            else{
                $('td.select-checkbox input').prop('checked',false);
                $('tr').find('td:last-child button').prop('disabled',false);
                $('.multi-add-to-cart').css('opacity','0');
            }
        });

        ////add single item to cart
        $(document).on('click','.single-add-to-cart',function(){
             $('.lds-spinner').show();
             $('body').css('opacity','0.7');
            var data = $(this).attr('data-val'); 
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url:"products/add",
                data:{
                    "product_id": data
                    },
                success:function(res){
                    setTimeout(function(){
                        $('body').css('opacity','1');
                        $('.lds-spinner').hide();
                        if(res.indexOf('Already') > -1){
                            iziToast.error({title: 'Error', message: res});
                        }else{
                        iziToast.success({timeout: 5000, icon: 'fas fa-check-circle', title: 'Added', message: res});
                      }
                      },1000)
                }
            })
        });


           ////add multi items to cart
           $(document).on('click','.multi-add-to-cart',function(){
             $('body').css('opacity','0.7');
             $('.lds-spinner').show();
            $('td.select-checkbox input:checked').each(function() {
               var _val = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: "POST",
                url:"products/add",
                async:"false",
                data:{
                    "product_id": _val
                    },
                success:function(res){
                    setTimeout(function(){
                        $('body').css('opacity','1');
                        $('.lds-spinner').hide();
                        if(res.indexOf('Already') > -1){
                            iziToast.error({title: 'Error', message: res});
                        }else{
                        iziToast.success({timeout: 5000, icon: 'fas fa-check-circle', title: 'Added', message: res});
                      }
                      },1000)
                }
            })
        });
    });


            // settings of toats
    iziToast.settings({
        timeout: 3000, // default timeout
        resetOnHover: true,
        // icon: '', // icon class
        transitionIn: 'flipInX',
        transitionOut: 'flipOutX',
        position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
        onOpen: function () {
          console.log('callback abriu!');
        },
        onClose: function () {
          console.log("callback fechou!");
        }
      });


      //// on write type change
      $('[name="type"]').change(function(){
          if($(this).val() == 'title'){
            $('.description-package').addClass('customhidden');
            $('.product-description').addClass('customhidden');
            $('.description-package').addClass('customhidden');
            $('.description-total').addClass('customhidden');
            $('.product-title').removeClass('customhidden');
            $('.title-total').removeClass('customhidden');
          }
          else{
            $('.description-package').removeClass('customhidden');
            $('.product-description').removeClass('customhidden');
            $('.description-package').removeClass('customhidden');
            $('.description-total').removeClass('customhidden');
            $('.product-title').addClass('customhidden');
            $('.title-total').addClass('customhidden');
          }
      })

            //// on description type change
            $('[name="destype"]').change(function(){
                var _val = $(this).find('option:selected').attr('dataval');
                var _items = $('.descount').text().trim();
                var _total = parseInt(_val)*parseInt(_items);
                $('span.description-amount').text('$'+_total+'.00');
                if(_total <= 15){
                 var extratotal = parseInt(_total)+parseInt(1);
                 $('.service-fee').removeClass('customhidden');
                 $('.description-total strong.total-amount').text('$'+extratotal+'.00')
                }
                else{
                 $('.service-fee').addClass('customhidden');
                 $('.description-total strong.total-amount').text('$'+_total+'.00')
                }
        })

        /// remove item on click
        $(document).on('click','.delete-item',function(){
           var id =   $(this).attr('data-val');
           var _self = $(this);
           $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            method: "POST",
            url:"products/delete",
            data:{
                "product_id": id
                },
            success:function(res){
                iziToast.success({timeout: 5000, icon: 'fas fa-check-circle', title: 'Deleted', message: res});
            $(_self).parents('.row.mb-4').hide();
            var typeval = $('[name="type"]').val();
                var _txt = $('.itemcount2').text();
                _txt  = parseInt(_txt)-parseInt(1);
                $('.itemcount2').text(_txt);
                var _totl = $('.titletotal').text().replace("$","");
                var itemtotal = parseFloat(_totl)-parseFloat(2);
                $('.titletotal').text('$'+itemtotal+'.00');
                var _final = $('.title-total strong.total-amount').text().replace("$","");
                var itemfinal;
                if(itemtotal <= 15){
                    if($('.service-fee').hasClass('customhidden')){
                  $('.service-fee').removeClass('customhidden');
                     itemfinal = parseFloat(_final)-parseFloat(2)+parseFloat('1');
                    }else{
                   itemfinal = parseFloat(_final)-parseFloat(2);
                 }
                }
                else{
                 itemfinal = parseFloat(_final)-parseFloat(2);
                }
                $('.title-total strong.total-amount').text('$'+itemfinal+'.00');

               var _items =  $('.descount').text();
               _items = parseInt(_items)-parseInt(1);
               $('.descount').text(_items);
               var _discount = $('[name="destype"]').find('option:selected').attr('dataval');
               var _totl = $('span.description-amount').text().replace("$","");
               var itemtotal = parseFloat(_totl)-parseFloat(_discount);
               $('span.description-amount').text('$'+itemtotal+'.00');
               var _final = $('.description-total strong.total-amount').text().replace("$","");
               var itemfinal;
               if(itemtotal <= 15){
                   if($('.service-fee').hasClass('customhidden')){
                 $('.service-fee').removeClass('customhidden');
                    itemfinal = parseFloat(_final)-parseFloat(_discount)+parseFloat('1');
                   }else{
                  itemfinal = parseFloat(_final)-parseFloat(_discount);
                }
               }
               else{
                itemfinal = parseFloat(_final)-parseFloat(_discount);
               }
               $('.description-total strong.total-amount').text('$'+itemfinal+'.00');
               var _txt = $('.itemcount').text();
               _txt  = parseInt(_txt)-parseInt('1');
               $('.itemcount').text(_txt);

            }
        })
        })

});
