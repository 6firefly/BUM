function invitationDialog(urlCreate, urlView){
    $.ajax({
      'url': urlCreate,
      'beforeSend':function(){
                      $('#Invite').dialog({
                          title:'Invitations',
                          modal:true,
                          autoOpen:false,
                          minWidth:630,
                          minHeight:690,
                          width:630,
                          height:690,
                          resizable:false,
                          buttons:{
                              'Send Invitation(s)':function(){sendInvitations( urlCreate, urlView, '#invitations-form'); }, // submit button
                              'Cancel':function(){ $(this).dialog('close');}
                          }
                      });
                      $('#dlg_invite_content').html('');// clear previous form
                      $('#Invite').dialog('open');
                      $('#AjaxLoader').show(); // display loading gif...
                   },
      'success':   function(response){
                       $('#dlg_invite_content').html(response);
                       $('#AjaxLoader').hide(); // hide loading gif
                   },
      'error':     function(e){
                       $('#dlg_invite_content').html('<DIV style="text-align:center;"><H4>Some error occure; refreshing the page might help!</H4></DIV>');
                       $('#AjaxLoader').hide(); // hide loading gif
                   }
    });
    $.ajax({
      'url': urlView,
      'success':   function(response){
                     $('#dlg_history_content').html(response);
                  }
    });
}

/**
 * This function submits the invitations-form. 
 */
function sendInvitations(urlCreate, urlView, formSelector) {
    $.ajax({
        'beforeSend':function(){
                         $('#AjaxLoader').show(); // display loading gif...
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
                                    $('#Invite').dialog('close');
                                    invitationDialog(urlCreate, urlView);
                                }else{
                                    $('#Invite').dialog('close');
                                }
                            }

                            // display errors    
                            for(var key in myObject){
                                $(formSelector + ' #'+key+'_em_').html(String(myObject[key]));
                                $(formSelector + ' #'+key+'_em_').show();
                            }
                        }
                        catch(e){
                            alert('No invitations left!');
                        }
 
                         $('#AjaxLoader').hide(); // hide loading gif
                     },
        'error':     function(error__) {
                         alert('Some error occurred!');
                     },
        'type' : 'POST',
        'url' : urlCreate,
        'cache' : false,
        'data' : $(formSelector).serialize()
    });
    return false;
}
