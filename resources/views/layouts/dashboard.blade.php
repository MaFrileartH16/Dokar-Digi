@extends('layouts.app')

@section('content')
  <div class="dashboard">
    <div class="page">
      <!--  BEGIN SIDEBAR  -->
      @include('components.sidebar', ['currentPage' => trim($__env->yieldContent('title'))])
      <!--  END SIDEBAR  -->

      <!--  BEGIN HEADER  -->
      @include('components.header')
      <!--  END HEADER  -->

      <div class="page-wrapper">
        <!-- BEGIN PAGE HEADER -->
        @yield('dashboard-header')
        <!-- END PAGE HEADER -->

        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
          <div class="container-xl">
            @yield('dashboard-content')
          </div>
        </div>
        <!-- END PAGE BODY -->

        <!--  BEGIN FOOTER  -->
        @include('components.footer')
        <!--  END FOOTER  -->
      </div>
    </div>
  </div>
@endsection
