@extends('layouts.admin')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <label>For Greater Than 20</label>  
<textarea id="content22" rows="10" name="mailbody" class="form-control" style="margin-bottom: 20px"></textarea>
<br>
<label>For Less Than 20</label>
<textarea id="content44" rows="10" name="mailbody2" class="form-control" style="margin-bottom: 20px"></textarea> 
<br>

<div class="row"> 
    <div class="checkbox">
        <label><input type="checkbox" name="democheck">  Send Test Email</label>
      </div>  
    <input style="display: none;" type="text" name="demoemail" class="form-control" placeholder="Enter Email">
</div> 
<br>
<div id="html_element" data-callback="recaptchaCallback"></div>    
<br> 
        <div class="row">
            @php
            $yms = array();
            $now = date('Y-m');
            for($x = 12; $x >= 1; $x--) {
                $ym = date('Y-m', strtotime($now . " -$x month"));
                $yms[$ym] = '<option>'.$ym.'</option>';
            }
            $yms = array_reverse($yms);
            echo "<select class='form-control monthprofit'>";
            print_r($yms);
            echo "</select>";
        @endphp
<br>
            <button type="button" class="btn btn-success emailsend" disabled="disabled">Send Email</button>  
            
        </div>
        <div class="row">
            <div id="table"></div>
        </div>
    </div>
</section>

@endsection
@push('script')
<script> 
function recaptchaCallback(){
   $('.emailsend').prop('disabled',false); 
}
function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
}
$('[name="democheck"]').change(function(){
    $('[name="demoemail"]').toggle();
})
    $('.emailsend').click(function(){
        var r = confirm("Are you sure you want to send email to all of the clients?");
  if (r == false) {
      return false; 
  }
        $('.lds-spinner').show();
        var _month = $('.monthprofit').val(); 
        var _text = $('[name="mailbody"]').val();
        var testemail = $('[name="demoemail"]').val();
        //  var _text = tinyMCE.activeEditor.getContent();
        var _text = tinymce.get("content22").getContent();
        var _text2 = tinymce.get("content44").getContent();
         var _auto = '';
         if($('[name="democheck"]').is(':checked')){
            _auto = 1;
            if( !isValidEmailAddress( testemail ) ) { 
          alert('Email not valid');  
        $('.lds-spinner').hide();
          return false;
           } 
         }else{
            _auto = 0;
         }
        $.ajaxSetup({ 
        headers: {
            'X-CSRF-TOKEN': $('[name="csrf-token"]').attr('content')
        }
        });
        $.ajax({
        method: "post",
        url:"/admin/email/getdata",  
        data:{
            "month": _month,
            'message':_text,
            'message2':_text2,
            'auto':_auto,
            'testemail':testemail 
            },
        success:function(res){
            $('.lds-spinner').hide();
            var _res = res.split('-'); 
            alert('Total '+_res[0]+' email sent under 20 and '+_res[1]+' over 20');   
            console.log(res);
        }
        })
    }) 
</script>
<style>
    .tox .tox-notification--warn, .tox .tox-notification--warning {
    display: none !important;
}
span.tox-statusbar__branding {
    display: none !important;
}
</style>
@endpush