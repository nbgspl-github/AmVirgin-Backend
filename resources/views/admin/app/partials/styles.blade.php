<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/icons.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/percircle.css')}}" rel="stylesheet">
<link href="{{asset('assets/admin/css/animate.css')}}" rel="stylesheet">
<link href="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('assets/admin/plugins/alertify/css/alertify.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/Notyf.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/admin/css/Selectize.css')}}" rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="{{asset("assets/admin/plugins/bootstrap-select/css/bootstrap-select.min.css")}}" rel="stylesheet">
<link href="{{asset("assets/admin/plugins/filepond/filepond.min.css")}}" rel="stylesheet">
<link href="{{asset("assets/admin/plugins/dropify/dist/css/dropify.min.css")}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.3/plyr.css"/>
<link
		href="https://unpkg.com/video.js@7/dist/video-js.min.css"
		rel="stylesheet"
/>
<link href="https://unpkg.com/@videojs/themes@1/dist/city/index.css" rel="stylesheet"/>
<style>
    .progress-bar-animated {
        -webkit-animation: progress-bar-stripes 0.25s linear infinite;
        animation: progress-bar-stripes 0.25s linear infinite;
    }

    .bootstrap-select {
        border: 1px solid #ced4da !important;
    }

    .bootstrap-select > button {
        background-color: white !important;
    }

    .bootstrap-select > button:active {
        border-color: #fd6e77;
    }

    .bootstrap-select .dropdown-toggle:focus, .bootstrap-select > select.mobile-device:focus + .dropdown-toggle {
        border-color: #fd6e77;
        /*box-shadow: 0 5px 8px rgba(207, 63, 67, 0.35);*/
        /*-webkit-box-shadow: 0 5px 8px rgba(207, 63, 67, 0.35);*/
        /*transform: translateY(-2px);*/
        /*transition: .3s;*/
    }

    @-webkit-keyframes zoomIn {
        from {
            opacity: 0;
            -webkit-transform: scale3d(0.3, 0.3, 0.3);
            transform: scale3d(0.3, 0.3, 0.3);
        }

        50% {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            -webkit-transform: scale3d(0.3, 0.3, 0.3);
            transform: scale3d(0.3, 0.3, 0.3);
        }

        50% {
            opacity: 1;
        }
    }

    .modal.fade {
        -webkit-animation-name: zoomIn;
        animation-name: zoomIn;
    }

    @-webkit-keyframes zoomOut {
        from {
            opacity: 1;
        }

        50% {
            opacity: 0;
            -webkit-transform: scale3d(0.3, 0.3, 0.3);
            transform: scale3d(0.3, 0.3, 0.3);
        }

        to {
            opacity: 0;
        }
    }

    @keyframes zoomOut {
        from {
            opacity: 1;
        }

        50% {
            opacity: 0;
            -webkit-transform: scale3d(0.3, 0.3, 0.3);
            transform: scale3d(0.3, 0.3, 0.3);
        }

        to {
            opacity: 0;
        }
    }

    .modal.fade.in {
        -webkit-animation-name: zoomOut;
        animation-name: zoomOut;
    }
</style>