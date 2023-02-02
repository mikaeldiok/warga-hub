@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
      <div class="block-30 block-30-sm item" style="background-image: url('/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
              <h2 class="heading">Our Gallery</h2>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <a href="/images/img_1.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_1.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_2.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_2.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_3.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_3.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_4.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_4.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>

        <div class="col-md-4">
          <a href="/images/img_1.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_1.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_2.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_2.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>

         <div class="col-md-4">
          <a href="/images/img_1.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_1.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_2.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_2.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_3.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_3.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_4.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_4.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>

        <div class="col-md-4">
          <a href="/images/img_1.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_1.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
        <div class="col-md-4">
          <a href="/images/img_2.jpg" class="img-hover" data-fancybox="gallery">
            <span class="icon icon-search"></span>
            <img src="/images/img_2.jpg" alt="Image placeholder" class="img-fluid">
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="featured-section overlay-color-2" style="background-image: url('/images/bg_2.jpg');">
    
    <div class="container">
      <div class="row">

        <div class="col-md-6 mb-5 mb-md-0">
          <img src="/images/bg_2.jpg" alt="Image placeholder" class="img-fluid">
        </div>

        <div class="col-md-6 pl-md-5">

          <div class="form-volunteer">
            
            <h2>Be A Volunteer Today</h2>
            <form action="#" method="post">
              <div class="form-group">
                <!-- <label for="name">Name</label> -->
                <input type="text" class="form-control py-2" id="name" placeholder="Enter your name">
              </div>
              <div class="form-group">
                <!-- <label for="email">Email</label> -->
                <input type="text" class="form-control py-2" id="email" placeholder="Enter your email">
              </div>
              <div class="form-group">
                <!-- <label for="v_message">Email</label> -->
                <textarea name="v_message" id="" cols="30" rows="3" class="form-control py-2" placeholder="Write your message"></textarea>
                <!-- <input type="text" class="form-control py-2" id="email"> -->
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-white px-5 py-2" value="Send">
              </div>
            </form>
          </div>
        </div>
        
      </div>
    </div>

  </div> <!-- .featured-donate -->
@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')


@endpush
