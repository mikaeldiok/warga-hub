@include ('frontend.includes.footer-comment-area')

<footer class="footer section pt-2 pt-md-2 pt-lg-4 pb-3 bg-primary text-white overflow-hidden">
    <div class="container">
        <hr class="my-4 my-lg-5">

        <div class="row">
            <div class="col pb-4 mb-md-0">
                <div class="d-flex text-center justify-content-center align-items-center">
                    <p class="font-weight-normal mb-0">
                        &copy; {{ app_name() }}, {!! setting('footer_text') !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
