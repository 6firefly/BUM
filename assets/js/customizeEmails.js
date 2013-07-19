/**
 * JavaScript code necessary for managing customize emails dialog box.
 *
 * @copyright	Copyright &copy; 2013 Hardalau Claudiu 
 * @package		bum
 * @license		New BSD License 
 * 
 */

function customizeEmailsDialog(urlCustomizeEmails){
    $.ajax({
      'url': urlCustomizeEmails,
      'beforeSend':function(){
                      $('#customize_email').dialog({
                          title:'Custimize ' + name + ' email',
                          modal:true,
                          autoOpen:false,
                          minWidth:630,
                          minHeight:700,
                          width:800,
                          height:690,
                          resizable:false,
                          buttons:{
                              'Save':function(){saveCustomizedEmails(urlCustomizeEmails); }, // submit button
                              'Cancel':function(){ $(this).dialog('close');}
                          }
                      });
                      $('#dlg_customize_email').html('');
                      $('#customize_email').dialog('open');
                      $('#AjaxLoader_costomize_email').show(); // display loading gif...
                   },
      'success':   function(response){
                       $('#dlg_customize_email').html(response);
                       $('#AjaxLoader_costomize_email').hide(); // hide loading gif
                   },
      'error':     function(e){
                        if (e.responseText){
                            alert(e.responseText);
                        }
                       $('#dlg_customize_email').html('<DIV style="text-align:center;"><H4>Some error occure; refreshing the page might help!</H4></DIV>');
                       $('#AjaxLoader_costomize_email').hide(); // hide loading gif
                   }
    });
}

/**
 * This function submits the invitations-form. 
 */
function saveCustomizedEmails(urlCustomizeEmails) {
    $.ajax({
        'url': urlCustomizeEmails,
        'beforeSend':function(){
                          $('#dlg_customize_email').html('');
                          $('#AjaxLoader_costomize_email').show(); // display loading gif...
                     },
        'success':   function(data) {
                        try{
                            $("div[id*='_em_']").hide(); // hide previous errors..

                            var myObject = eval('(' + data + ')');

                            if(myObject.hasOwnProperty('message')){
                               alert(myObject.message);
                            }
                            if(myObject.hasOwnProperty('status')){
                                if(myObject.status == 'ok'){
                                    $('#customize_email').dialog('close');
                                }else{
                                    $('#customize_email').dialog('close');
                                }
                            }

                            // display errors    
                            for(var key in myObject){
                                $('#site-emails-content-form #'+key+'_em_').html(String(myObject[key]));
                                $('#site-emails-content-form #'+key+'_em_').show();
                            }
                        }
                        catch(e){
                            $('#dlg_customize_email').html('<DIV style="text-align:center;"><H4>Some error occure; refreshing the page might help!</H4></DIV>');
                            $('#AjaxLoader_costomize_email').hide(); // hide loading gif
                        }
 
                         $('#AjaxLoader_costomize_email').hide(); // hide loading gif
                     },
        'error':     function(error__) {
                         alert('Some error occurred!');
                     },
        'type' : 'POST',
        'cache' : false,
        'data' : $('#site-emails-content-form').serialize()
    });
    return false;
}
