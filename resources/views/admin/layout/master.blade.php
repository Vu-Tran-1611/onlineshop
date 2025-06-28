<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>General Dashboard &mdash; Stisla</title>
    {{-- Icon picker --}}

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" />

    <link rel="stylesheet" href="{{ asset('admin/css/bootstrap-iconpicker.min.css') }}" />
    {{-- Icon picker --}}
    {{-- Toastify --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('admin/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('admin/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/modules/summernote/summernote-bs4.css') }}">
    {{-- Selectric --}}
    <link rel="stylesheet" href="{{ asset('admin/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/modules/jquery-selectric/selectric.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/components.css') }}">
    {{-- Datatable  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            {{-- Navbar --}}
            @include('admin.layout.navbar')

            {{-- Sidebar --}}
            @include('admin.layout.sidebar')


            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="main-footer">
                <div class="footer-left">
                    &copy; {{ date('Y') }} <a href="{{ url('/') }}">OnlineShop</a>. All rights reserved.
                </div>
                <div class="footer-right">
                    Powered by <a href="https://laravel.com/" target="_blank">Laravel</a>
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('admin/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/modules/popper.js') }}"></script>
    <script src="{{ asset('admin/modules/tooltip.js') }}"></script>
    <script src="{{ asset('admin/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('admin/modules/moment.min.js') }}"></script>
    <script src="{{ asset('admin/js/stisla.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    {{-- Icon picker --}}
    <!-- Bootstrap CDN -->
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js">
    </script>
    <!-- Bootstrap-Iconpicker Bundle -->
    <script type="text/javascript" src="{{ asset('admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
    {{-- Toastify --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    {{-- Sweat Alert  --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Fontawesom Icon --}}
    <script src="https://kit.fontawesome.com/1027857984.js" crossorigin="anonymous"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('admin/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('admin/modules/chart.min.js') }}"></script>
    <script src="{{ asset('admin/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('admin/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('admin/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('admin/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    {{-- Selectric --}}
    <script src="{{ asset('admin/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/modules/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('admin/js/page/index-0.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('admin/js/scripts.js') }}"></script>
    <script src="{{ asset('admin/js/custom.js') }}"></script>
    {{-- Datatable --}}
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $err)

                Toastify({
                    text: "{{ $err }}",
                    duration: 3000,
                    className: "info",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    }
                }).showToast();
            @endforeach
        @endif
        @if (Session::has('status'))
            Toastify({
                text: "{{ session('status') }}",
                duration: 3000,
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        @endif
    </script>
    <script>
        $(document).ready(function() {
            // ------------------------------ Change serial --------------------------------- 

            $("body").on("change", ".serial", function() {
                const serial = $(this).val();
                const url = $(this).data("url");
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: {
                        serial: serial
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 'success') {
                            Toastify({
                                text: data.message,
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                                }
                            }).showToast();
                        }
                    },
                });
            })

            // ------------------------------ Change Status --------------------------------- 
            $("body").on("change", ".status", function() {

                const currentStatus = $(this).data("status");
                const phone = $(this).data('user-phone');
                const URL = $(this).data("url");
                const status = $(this).val()
                const id = $(this).data("id");
                const selectName = $(this).data("name");
                const data = {
                    key: status
                };
                let text = "";
                let confirmButtonText = "Yes, I Agree";

                // Set up button text for schedule status select
                if (selectName == "schedule-status") {
                    [text, confirmButtonText] = setUpScheduleStatusText();
                }

                function setUpScheduleStatusText() {
                    let text = "";
                    let confirmButtonText = "Agree!";
                    if (status == "confirmed") {
                        text = `You should call this number <b><u>${phone}</u></b> to confirm the schedule`;
                        confirmButtonText = "Yes, I've already called the patient!";
                    } else if (status == "canceled") {
                        text = "Give the reason to the patient why you cancel this schedule";
                    } else if (status == "completed") {
                        text = "Give some notes to the patient after schedule";
                    } else {
                        text = "Give some notes to the patient to prepare for the schedule";
                    }
                    return [text, confirmButtonText];
                }

                // Reset Status if  canceling the request
                resetStatus = () => {
                    $(`.select-status-${id} option[value=${currentStatus}]`).prop("selected", 'true');
                }

                // Chang status by send AJAX 
                function changeStatus(data = null, text = null) {
                    $.ajax({
                        type: "PUT",
                        async: false,
                        url: URL,
                        data: data,
                        dataType: "JSON",
                        success: function(data) {

                            if (data.status == 'success_show') {
                                Swal.fire({
                                    title: "Updated!",
                                    text: data.text,
                                    icon: "success"
                                });
                            }

                            if (data.status == 'success') {
                                Toastify({
                                    text: data.message,
                                    className: "info",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                                    }
                                }).showToast();
                            }

                            if (data.status == 'hide') {
                                Swal.fire({
                                    title: "Updated!",
                                    text: "The status has changed.",
                                    icon: "success"
                                });
                                $(`.select-status-${data.id}`).parents().eq(2).hide(3000);
                            }


                            if (data.is_empty == true) {
                                const html =
                                    '<td valign="top" colspan="6" class="dataTables_empty">No data available in table</td>';
                                $("tbody").html(html);
                            }

                        },
                    });
                }

                // Update Role Status 
                if (selectName == "role-status") {
                    Swal.fire({
                        title: "Are you sure?",
                        html: text,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: confirmButtonText,
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            resetStatus();
                        } else {
                            changeStatus(data);
                        }
                    })
                }
                // Update Role Status 
                else if (selectName == "schedule-status") {
                    Swal.fire({
                        title: "Are you sure?",
                        html: text,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: confirmButtonText,
                    }).then((result) => {
                        if (!result.isConfirmed) {
                            resetStatus();
                        } else {
                            if (status != "confirmed") {
                                async function writeNote() {
                                    const {
                                        value: text
                                    } = await Swal.fire({
                                        input: "textarea",
                                        inputLabel: "Message",
                                        inputPlaceholder: "Type your message here...",
                                        inputAttributes: {
                                            "aria-label": "Type your message here"
                                        },
                                        showCancelButton: true
                                    });
                                    if (text) {
                                        Swal.fire({
                                            title: text,
                                            confirmButtonText: "Send",
                                        });
                                        changeStatus({
                                            ...data,
                                            text
                                        });
                                    } else resetStatus();
                                }
                                writeNote();
                            } else {
                                changeStatus(data);
                            }
                        }
                    });
                } else changeStatus();
            })
            // ------------------------------ Delete Items --------------------------------- 
            $("body").on("click", ".delete", function() {
                const URL = $(this).data("url");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: URL,
                            dataType: "JSON",
                            success: (data) => {
                                if (data.status == "success") {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: data.message,
                                        icon: "success"
                                    });
                                    $(this).parent().parent().hide();
                                    if (data.is_empty == true) {
                                        const html =
                                            '<td valign="top" colspan="6" class="dataTables_empty">No data available in table</td>';
                                        $("tbody").html(html);
                                    }
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "There is something wrong",
                                        text: data.message,
                                        footer: "<a href=' {{ route('admin.sub-category.index') }} '>Go To Sub Categories</a>"

                                    });
                                }
                            },
                        });

                    }
                });

            })
            // ---------------------------------  change default --------------------------------- 
            $('body').on('click', '.isdefault', function() {

                const URL = $(this).data("url");
                $.ajax({
                    type: "PUT",
                    url: URL,
                    dataType: "JSON",
                    success: function(data) {

                        Toastify({
                            text: data.status,
                            duration: 3000,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            }
                        }).showToast();

                    }
                });
            })
            // --------------------------------- Get sub categories --------------------------------- 
            $("body").on("change", ".main_category", function() {
                $(".child_category").html("<option value=''> Select </option>");

                let id = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.category.get-sub-categories') }}",
                    data: {
                        categoryID: id
                    },
                    dataType: "JSON",
                    success: function(data) {
                        $(".sub_category").html("<option value=''> Select </option>");
                        $.each(data.subCategories, function(index, value) {
                            $(".sub_category").append(
                                `<option value = ${value.id}>${value.name}</option>`
                            );
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Can't get data")
                    }
                });
            });
            // -------------------------------- Change product type --------------------------------
            $("body").on("change", ".product_type", function() {
                let id = $(this).data("id");
                let type = $(this).val();
                $.ajax({
                    type: "PUT",
                    url: "{{ route('admin.product.change_type') }}",
                    data: {
                        productType: type,
                        productID: id,
                    },
                    dataType: "JSON",
                    success: function(data) {
                        Toastify({
                            text: data.status,
                            duration: 3000,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            }
                        }).showToast();

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.table(jqXHR)
                    }
                });
            });
            // -------------------------------- Change Product approved --------------------------------
            $("body").on("change", ".product_approved", function() {
                status = $(this).val();
                id = $(this).data("id");
                $.ajax({
                    type: "PUT",
                    url: "{{ route('admin.product.change_product_approved') }}",
                    data: {
                        productStatus: status,
                        productID: id,
                    },
                    dataType: "JSON",
                    success: function(data) {
                        Toastify({
                            text: "Updated status successfully",
                            duration: 3000,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            }
                        }).showToast();

                    },
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
