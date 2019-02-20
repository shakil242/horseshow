
    <footer class="text-center main-footer"><a href="#">EQUETICA</a> All Rights Reserved © Copyrights 2018</footer>


    <div class="sticky-main-tabs"><a class="btn btn-primary btn-feedback" href="#">Feedback</a></div>
    <div class="sticky-right-panel">

        <div class="sticky-right-content-panel">
            <div class="close"><img src="{{ asset('/img/icons/icon-close.svg')}}" /></div>
            <h5 class="text-info">Feedback</h5>

            <div  id="feedbackModal"></div>

            {!! Form::open(['url'=>'#','method'=>'post', 'id'=>'feedback-form','class'=>'form']) !!}
                <div class="row">
                    <div class="col-sm-12">
                        <label for="val-1" class="mr-sm-2 label">What do you like about the application?</label>
                        <input id="val-1" name="qno1" class="form-control" type="text" placeholder="" />
                    </div>
                    <div class="col-sm-12">
                        <label for="val-2" class="mr-sm-2 label">What do you dislike / didn't like about the application?</label>
                        <input id="val-2" name="qno2" class="form-control" type="text" placeholder="" />
                    </div>
                    <div class="col-sm-12">
                        <label for="val-3" class="mr-sm-2 label">Any ideas to improve the application?</label>
                        <input id="val-3" name="qno3" class="form-control" type="text" placeholder="" />
                    </div>
                    <div class="col-sm-12">
                        <label for="val-4" class="mr-sm-2 label">Any issues faced in the application?</label>
                        <input for="val-4" name="qno4" class="form-control" type="text" placeholder="" />
                    </div>
                    <div class="col-sm-12  ">
                        <div class="btn-group d-flex">
                            <button class="btn btn-default btn-small w-100 mr-5 close" type="button">Cancel</button>
                            {!! Form::submit("Submit" , ['class' =>"btn btn-small btn-secondary w-100 ml-5"]) !!}

                        </div>
                    </div>
                </div>
            {!! Form::close() !!}

        </div>
    </div>

    <script>
        setRightPanel();
        $('[data-toggle="tooltip"]').tooltip();

        function setRightPanel(){
            var headerHeight = 0;
            headerHeight = $(".main-header").height();
            if($(window).width()<=991){
                headerHeight = $(".header-responsive").height();
            }
            $(".sticky-right-content-panel").css("top",headerHeight+'px');
        }
        $(document).ready(function(){

            $('.btn-feedback').on('click',function (){
                $('.sticky-right-panel').addClass ('active');
                $('.overlay-full').addClass('active');
            })

            $('.sticky-right-content-panel .close, .overlay-full').on('click',function (){
                $('.sticky-right-panel').removeClass ('active')
                $('.overlay-full').removeClass('active');
            })
        });

        $(window).on("resize",function(){
            setRightPanel();
        })
    </script>
