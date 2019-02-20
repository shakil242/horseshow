$(document).on("click",".modules-clicked",function( event ) {
                        event.preventDefault();
                        var template_id = $(this).attr("template-id");
                        var module_id = $(this).attr("module-id");
                        var base_url = window.location.protocol + "//" + window.location.host + "/";

                        $.ajax({
                            url: base_url + 'ranking/'+template_id+'/'+module_id+'/getranked',
                            type: 'get',
                            dataType: 'html',
                            beforeSend: function()
                            {
                                $('#ajax-loading').show();
                            },
                            success: function( html ){
                              
                              if (html == 1) {
                                location.href = base_url + 'ranking/'+template_id+'/'+module_id+'/'+"module_wise";
                                event.preventDefault();
                              }else{
            				$('#ajax-loading').hide();
                            $('.back-to-all').show();
                            
                                          $(".ajax-renders").html(html);// Handle your response..
            				}
                             
                            },
                            error: function( _response ){
                                $('#ajax-loading').hide();
                            }
                        });
                    });

$(document).on("click",".back-to-all-modules",function( event ) {
                        event.preventDefault();
                        var template_id = $(this).attr("template-id");
                        var base_url = window.location.protocol + "//" + window.location.host + "/";

                        $.ajax({
                            url: base_url + 'ranking/'+template_id+'/modules-back',
                            type: 'get',
                            dataType: 'html',
                            beforeSend: function()
                            {
                                $('#ajax-loading').show();
                            },
                            success: function( html ){

                              $('#ajax-loading').hide();
                                $('.back-to-all').hide(); 

                              $(".ajax-renders").html(html);// Handle your response..
                            },
                            error: function( _response ){
                                $('#ajax-loading').hide();
                            }
                        });
                    });
