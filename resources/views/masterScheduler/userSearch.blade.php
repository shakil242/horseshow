
<link rel="stylesheet" href="{{ asset('/ajaxLive/css/fontello.css') }}">
<link rel="stylesheet" href="{{ asset('/ajaxLive/css/animation.css') }}">
<!--[if IE 7]>
<link rel="stylesheet" href="{{ asset('/ajaxLive/css/fontello-ie7.css') }}">
<![endif]-->
<link rel="stylesheet" type="text/css" href="{{ asset('/ajaxLive/css/ajaxlivesearch.min.css') }}">

        <div class="row">

            <input type="hidden" id="csrf-token" value="{{csrf_token()}}">
            <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="templateId">

            <input type="hidden" value="{{$variables['spectatorsId']}}" name="spectatorsId"  id="spectatorsId">

            <input type="hidden" value="{{$variables['show_id']}}" name="show_id"  id="show_id_slots">

            <div class="col-sm-12" style="margin-bottom: 20px;">
                    <input type="text" class='mySearch form-control' id="ls_query" placeholder="Type to start searching ...">
                    <!-- /btn-group -->
            </div>
        </div>
<script type="text/javascript" src="{{ asset('/ajaxLive/js/ajaxlivesearch.min.js') }}"></script>
<script>
    jQuery(document).ready(function(){
        jQuery(".mySearch").ajaxlivesearch({
            loaded_at:'<?php echo time(); ?>',
            token: '{{csrf_token()}}',
            max_input: '',
            template_id :"{{$variables['templateId']}}",
            spectatorsId :"{{$variables['spectatorsId']}}",
            show_id :"{{$variables['show_id']}}",

            onResultClick: function(e, data) {
                // get the index 0 (first column) value
               // var selectedOne = jQuery(data.selected).find('td').eq('0').text();

                // set the input value
               // jQuery('#ls_query').val(selectedOne);

                // hide the result
               // jQuery("#ls_query").trigger('ajaxlivesearch:hide_result');
            },
            onResultEnter: function(e, data) {
                // do whatever you want
                // jQuery("#ls_query").trigger('ajaxlivesearch:search', {query: 'test'});
            },
            onAjaxComplete: function(e, data) {

              //  console.log(e);

            }
        });
    })
</script>

<style>
    .ls_query{ width: 100%;}
    .ls_result_div table tr {
        font-size: 13px;
        line-height: 22px;
        text-align: left;
    }

    .ls_result_div table tr td, .ls_result_div table tr th
    {
        text-align: left;

    }

   .ls_result_div table tr th:first-child
   {
       padding-left: 10px;
   }
    .ls_result_div
    {
        background: #ededed;
    }

</style>