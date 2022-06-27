

//jQuery(document).ready(function(){
//    jQuery(document).on('submit','#my_qzquestions',function(e){
//       e.preventDefault();
//       var  qs_name =  jQuery('#qs_name').val();
//       var  dsc =  jQuery('#description_id').val();
//       var  status =  jQuery('#status').val();
//       jQuery.ajax({
//            type : "post",
//            url : Ajaxquiz.ajaxurl,
//            data : {action: "user_questions", qs_name: qs_name, dsc: dsc, status: status},
//            success: function($data) {
//              jQuery("#quesn_result").html($data);
//            }
//        });
//         return false;
//    });
//});