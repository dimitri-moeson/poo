/**
tested
if (jQuery) {
    alert("jquery is loaded");
} else {
    alert("Not loaded");
}
***/

showDebug('');

/**
 *
 */
function showDebug(id){

     $("div.debug").each(function( index ) {

         console.log("compare" + index + ": " + $(this).attr('id')+"/"+id+"-debug");
         if (  $(this).attr('id') != (id+"-debug") ) {
             console.log("foreach" + index + ": " + $(this).attr('id'));
             $(this).hide(); //css('display','none');
         }
    });

     if(id != '')
     {
         console.log("debug::param::" + id);

         var box = $('div#' + id + '-debug');

         if (box.length > 0) {
             console.log("debug::exists::" + id);

             if (box.is(':visible')) //|| box.is(':not(:hidden)') //if(box.css("display") == 'none')
             {
                 box.hide(); //css('display','none');
             }
             else if (box.is(':hidden'))
             {
                 box.show(); //css('display','block');
             }
         }
     }
    return false ;
}

