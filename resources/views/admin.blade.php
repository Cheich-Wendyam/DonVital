
@extends('layouts.layout')
@section('content')
       
<div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">DonVital</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

                        <div class="row">
                            <div class="col-xl-3">
                                <div class="card-box">
                                    <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                                    <h4 class="mt-0 font-16">Wallet Balance</h4>
                                    <h2 class="text-primary my-4 text-center">$<span data-plugin="counterup">31,570</span></h2>
                                    <div class="row mb-4">
                                        <div class="col-6">
                                            <p class="text-muted mb-1">This Month</p>
                                            <h3 class="mt-0 font-20 text-truncate">$120,254 <small class="badge badge-light-success font-13">+15%</small></h3>
                                        </div>

                                        <div class="col-6">
                                            <p class="text-muted mb-1">Last Month</p>
                                            <h3 class="mt-0 font-20 text-truncate">$98,741 <small class="badge badge-light-danger font-13">-5%</small></h3>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <span data-plugin="peity-line" data-fill="#56c2d6" data-stroke="#4297a6" data-width="100%" data-height="50">3,5,2,9,7,2,5,3,9,6,5,9,7</span>
                                    </div>
    
                                </div> <!-- end card-box-->
                            </div>
                            
                            <div class="col-xl-6">
                                <div class="card-box" dir="ltr">
                                    <div class="float-right d-none d-md-inline-block">
                                        <div class="btn-group mb-2">
                                            <button type="button" class="btn btn-xs btn-secondary">Today</button>
                                            <button type="button" class="btn btn-xs btn-light">Weekly</button>
                                            <button type="button" class="btn btn-xs btn-light">Monthly</button>
                                        </div>
                                    </div>
                                    <h4 class="header-title mb-1">Transaction History</h4>
                                    <div id="rotate-labels-column" class="apex-charts"></div>
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->

                            
                        </div>
                        <!-- end row -->


                        <div class="row">
                            <div class="col-xl-8">
                                <!-- Portlet card -->
                                <div class="card">
                                    <div class="card-body" dir="ltr">
                                        <div class="card-widgets">
                                            <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                            <a data-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                                            <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                        </div>
                                        <h4 class="header-title mb-0">Revenue</h4>

                                        <div id="cardCollpase1" class="collapse pt-3 show">
                                            <div class="bg-soft-light border-light border">
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <p class="text-muted mb-0 mt-3">Today's Earning</p>
                                                        <h2 class="font-weight-normal mb-3">
                                                            <small class="mdi mdi-checkbox-blank-circle text-muted align-middle mr-1"></small>
                                                            <span>$751.<sup class="font-13">25</sup></span>
                                                        </h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="text-muted mb-0 mt-3">Current Week</p>
                                                        <h2 class="font-weight-normal mb-3">
                                                            <small class="mdi mdi-checkbox-blank-circle text-info align-middle mr-1"></small>
                                                            <span>$2,874.<sup class="font-13">07</sup></span>
                                                        </h2>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="text-muted mb-0 mt-3">Previous Week</p>
                                                        <h2 class="font-weight-normal mb-3">
                                                            <small class="mdi mdi-checkbox-blank-circle text-danger align-middle mr-1"></small>
                                                            <span>$1,258.<sup class="font-13">66</sup></span>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dash-item-overlay d-none d-md-block">
                                                <h5>Today's Earning: $751.25</h5>
                                                <p class="text-muted font-13 mb-3 mt-2">Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget...</p>
                                                <a href="javascript: void(0);" class="btn btn-primary">View Statements
                                                    <i class="mdi mdi-arrow-right ml-2"></i>
                                                </a>
                                            </div>
                                            <div id="apex-line-1" class="apex-charts" style="min-height: 480px !important;"></div>
                                        </div> <!-- collapsed end -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-widgets">
                                            <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                            <a data-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                                            <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                        </div>
                                        <h4 class="header-title mb-0">Orders Analytics</h4>

                                        <div id="cardCollpase2" class="collapse pt-3 show" dir="ltr">
                                            <div id="radar-multiple-series" class="apex-charts"></div>
                                        </div> <!-- collapsed end -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->

                                <div class="card cta-box bg-info text-white">
                                    <div class="card-body">
                                        <div class="media align-items-center">
                                            <div class="media-body">
                                                <div class="avatar-md bg-soft-light rounded-circle text-center mb-2">
                                                    <i class="mdi mdi-store font-22 avatar-title text-light"></i>
                                                </div>
                                                <h3 class="m-0 font-weight-normal text-white sp-line-1 cta-box-title">Special launcing <b>Discount</b> offer</h3>
                                                <p class="text-light mt-2 sp-line-2">Suspendisse vel quam malesuada, aliquet sem sit amet, fringilla elit. Morbi tempor tincidunt tempor. Etiam id turpis viverra.</p>
                                                <a href="javascript: void(0);" class="text-white font-weight-semibold text-uppercase">Read More <i class="mdi mdi-arrow-right"></i></a>
                                            </div>
                                            <img class="ml-3" src="{{asset('images/update.svg')}}" width="120" alt="Generic placeholder image">
                                        </div>
                                    </div>
                                    <!-- end card-body -->
                                </div>
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-xl-9">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-widgets">
                                            <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                            <a data-toggle="collapse" href="#cardCollpase4" role="button" aria-expanded="false" aria-controls="cardCollpase4"><i class="mdi mdi-minus"></i></a>
                                            <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                        </div>
                                        <h4 class="header-title mb-0">Revenue by Location</h4>

                                        <div id="cardCollpase4" class="collapse pt-3 show">
                                            <div class="row">
                                                <div class="col-md-8 align-self-center">
                                                    <div id="usa-map"  style="height: 350px" class="dash-usa-map"></div>
                                                </div> <!-- end col -->
                                                <div class="col-md-4 align-self-center">
                                                    <h5 class="mb-1 mt-0">1,12,540 <small class="text-muted ml-2">www.getbootstrap.com</small></h5>
                                                    <div class="progress-w-percent">
                                                        <span class="progress-value font-weight-bold">72% </span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                
                                                    <h5 class="mb-1 mt-0">51,480 <small class="text-muted ml-2">www.youtube.com</small></h5>
                                                    <div class="progress-w-percent">
                                                        <span class="progress-value font-weight-bold">39% </span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 39%;" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                
                                                    <h5 class="mb-1 mt-0">45,760 <small class="text-muted ml-2">www.dribbble.com</small></h5>
                                                    <div class="progress-w-percent">
                                                        <span class="progress-value font-weight-bold">61% </span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: 61%;" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                
                                                    <h5 class="mb-1 mt-0">98,512 <small class="text-muted ml-2">www.behance.net</small></h5>
                                                    <div class="progress-w-percent">
                                                        <span class="progress-value font-weight-bold">52% </span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 52%;" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                
                                                    <h5 class="mb-1 mt-0">2,154 <small class="text-muted ml-2">www.vimeo.com</small></h5>
                                                    <div class="progress-w-percent mb-0">
                                                        <span class="progress-value font-weight-bold">28% </span>
                                                        <div class="progress progress-sm">
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 28%;" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div> <!-- end col -->
                                            </div> <!-- end row-->
        
                                        </div> <!-- collapsed end -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                            <div class="col-xl-3">
                                <!-- Portlet card -->
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-widgets">
                                            <a href="javascript: void(0);" data-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                                            <a data-toggle="collapse" href="#cardCollpase3" role="button" aria-expanded="false" aria-controls="cardCollpase3"><i class="mdi mdi-minus"></i></a>
                                            <a href="javascript: void(0);" data-toggle="remove"><i class="mdi mdi-close"></i></a>
                                        </div>
                                        <h4 class="header-title mb-0">Recent Activities</h4>

                                        <div id="cardCollpase3" class="collapse pt-3 show">
                                            <div class="slimscroll" style="max-height: 350px;">
                                                <div class="timeline-alt">
                                                    <div class="timeline-item">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <a href="#" class="text-body font-weight-semibold mb-1 d-block">You sold an item</a>
                                                            <small>Paul Burgess just purchased “Upvex - Admin Dashboard”!</small>
                                                            <p>
                                                                <small class="text-muted">5 minutes ago</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <a href="#" class="text-body font-weight-semibold mb-1 d-block">Product on the Bootstrap Market</a>
                                                            <small>Dave Gamache added
                                                                <span class="font-weight-medium">Admin Dashboard</span>
                                                            </small>
                                                            <p>
                                                                <small class="text-muted">30 minutes ago</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <a href="#" class="text-body font-weight-semibold mb-1 d-block">Robert Delaney</a>
                                                            <small>Send you message
                                                                <span class="font-weight-medium">"Are you there?"</span>
                                                            </small>
                                                            <p>
                                                                <small class="text-muted">2 hours ago</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <a href="#" class="text-body font-weight-semibold mb-1 d-block">Audrey Tobey</a>
                                                            <small>Uploaded a photo
                                                                <span class="font-weight-semibold">"Error.jpg"</span> Please change folder structure.
                                                            </small>
                                                            <p>
                                                                <small class="text-muted">14 hours ago</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="timeline-item">
                                                        <i class="timeline-icon"></i>
                                                        <div class="timeline-item-info">
                                                            <a href="#" class="text-body font-weight-semibold mb-1 d-block">You sold an item</a>
                                                            <small>Paul Burgess just purchased “Upvex - Admin Dashboard”!</small>
                                                            <p>
                                                                <small class="text-muted">1 day ago</small>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- end timeline -->
                                            </div> <!-- end slimscroll -->
                                        </div> <!-- collapsed end -->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row -->


                           
                        </div>
                        
                    </div> <!-- container -->

                </div> <!-- content -->

             <!-- Vendor js -->
        <script src="{{asset('js/vendor.min.js')}}"></script>

<!-- Third Party js-->
<script src="{{asset('libs/peity/jquery.peity.min.js')}}"></script>
<script src="{{asset('libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('libs/jquery-vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('libs/jquery-vectormap/jquery-jvectormap-us-merc-en.js')}}"></script>

<!-- Dashboard init -->
<script src="{{asset('js/pages/dashboard-1.init.js')}}"></script>

<!-- App js -->
<script src="{{asset('js/app.min.js')}}"></script>
@endsection