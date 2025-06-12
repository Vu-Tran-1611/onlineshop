{{-- filepath: c:\laragon\www\onlineshop\resources\views\frontend\pages\profile-address.blade.php --}}

@extends('frontend.layout.profile')

@section('profile-content')
    {{-- Address Section --}}
    <div class="p-8 bg-gradient-to-br from-sky-900 to-white rounded-2xl shadow-xl">
        <div class="flex justify-between items-center border-b pb-6 mb-6">
            <h2 class="text-3xl font-extrabold text-white flex items-center gap-2">
                <i class="fa-solid fa-location-dot"></i> My Addresses
            </h2>
            <button
                class="register-address bg-gradient-to-r from-sky-900 to-blue-500 hover:from-sky-700 hover:to-blue-900 text-white rounded-xl py-2 px-6 shadow-lg font-semibold transition-all duration-200">
                <i class="fa-solid fa-plus mr-2"></i> Add New Address
            </button>
        </div>
        <div class="mt-8">
            <div class="addresses grid md:grid-cols-2 gap-8">
                @forelse ($addresses as $addr)
                    <div
                        class="address-item relative flex flex-col gap-4 p-6 bg-white rounded-2xl shadow-lg border border-sky-100 hover:shadow-2xl transition-all duration-200">
                        @if ($addr->is_default)
                            <span
                                class="absolute top-4 right-4 text-xs font-bold text-white bg-gradient-to-r from-sky-500 to-blue-800 px-3 py-1 rounded-full shadow">
                                Default
                            </span>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-sky-700 flex items-center gap-2">
                                <i class="fa-solid fa-user"></i> {{ $addr->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-phone"></i> {{ $addr->phone }}</p>
                            <p class="text-sm text-gray-500"><i class="fa-solid fa-map-pin"></i> {{ $addr->address }}</p>
                            <p class="text-sm text-gray-500">
                                <i class="fa-solid fa-earth-americas"></i>
                                {{ $addr->country }}, {{ $addr->state }} State, {{ $addr->city }} City,
                                {{ $addr->zip }}
                            </p>
                            <span class="inline-block mt-2 text-xs font-medium text-white bg-sky-800 px-2 py-1 rounded">
                                {{ ucfirst($addr->type) }}
                            </span>
                        </div>
                        <div class="flex gap-3 mt-4">
                            <button data-id="{{ $addr->id }}"
                                class="edit flex items-center gap-1 text-sky-900 hover:text-sky-800 font-semibold transition">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button data-url="{{ route('user.address.destroy', $addr->id) }}"
                                class="delete flex items-center gap-1 text-red-900 hover:text-red-800 font-semibold transition">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                            <button data-url="{{ route('user.address.set-default', $addr->id) }}"
                                class="set-default flex items-center gap-1 py-1 px-3 rounded-lg text-sm font-medium
                                {{ $addr->is_default ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-sky-900 to-blue-500 text-white hover:from-sky-700 hover:to-blue-900' }}"
                                {{ $addr->is_default ? 'disabled' : '' }}>
                                <i class="fa-solid fa-star"></i> Set Default
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-800 py-10">
                        <i class="fa-solid fa-box-open text-4xl mb-2"></i>
                        <div>No addresses found. Add your first address!</div>
                    </div>
                @endforelse

            </div>
            <div class="mt-8 col-span-2">
                {{ $addresses->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    {{-- Freeze Screen --}}
    <div class="freeze-screen hidden fixed inset-0 bg-black bg-opacity-40 z-40"></div>

    {{-- Add New Address Modal --}}
    <div class="show-address hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative">
            <button class="hide-address absolute top-4 right-4 text-gray-800 hover:text-gray-700 text-xl">&times;</button>
            <h2 class="text-2xl font-bold mb-6 text-sky-700 flex items-center gap-2">
                <i class="fa-solid fa-location-dot"></i> New Address
            </h2>
            <form class="add-address space-y-5">
                <div class="flex gap-4">
                    <input name="name" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="Full Name" />
                    <input name="phone" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="Phone Number" />
                </div>
                <div class="flex gap-4">
                    <input name="country" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="Country" />
                    <input name="state" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="State" />
                </div>
                <div class="flex gap-4">
                    <input name="city" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="City" />
                    <input name="zip" class="w-1/2 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                        type="text" placeholder="Zip Code" />
                </div>
                <input name="address" class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800"
                    type="text" placeholder="Address" />
                <div>
                    <label class="block text-sm font-medium mb-1">Label As:</label>
                    <select name="type"
                        class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800">
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_default" value="true" class="accent-sky-900" />
                    <label class="text-gray-700">Set As Default Address</label>
                </div>
                <div class="flex justify-end gap-4 pt-2">
                    <button type="button"
                        class="hide-address py-2 px-6 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold">Cancel</button>
                    <button
                        class="py-2 px-6 bg-gradient-to-r from-sky-900 to-blue-500 text-white hover:from-sky-700 hover:to-blue-900 rounded-lg font-semibold shadow"
                        type="submit">
                        <i class="fa-solid fa-check mr-1"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Show Add Address Modal
            $(".register-address").on("click", function() {
                $(".show-address").removeClass("hidden").addClass("flex");
                $(".freeze-screen").removeClass("hidden");
            });
            // Hide Add Address Modal
            $(".hide-address").on("click", function(e) {
                e.preventDefault();
                $(".show-address").addClass("hidden").removeClass("flex");
                $(".freeze-screen").addClass("hidden");
            });

            // Set Default Address
            $("body").on("click", ".set-default", function() {
                const url = $(this).data("url");
                $.ajax({
                    type: "PUT",
                    url: url,
                    dataType: "JSON",
                    success: (response) => {
                        if (response.status === 'success') {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                                }
                            }).showToast();
                            location.reload(); // Reload to update default status
                        }
                    }
                });
            });

            // Edit Address Modal (if you have edit modals per address)
            $("body").on("click", ".edit", function() {
                const id = $(this).data("id");
                $(".freeze-screen").removeClass("hidden");
                $(".edit-address-" + id).removeClass("hidden");
            });
            $("body").on("click", ".hide-edit-address", function(e) {
                e.preventDefault();
                $(".freeze-screen").addClass("hidden");
                $(".edit-address").addClass("hidden");
            });

            // Update Address
            $("body").on("submit", ".update-address", function(e) {
                e.preventDefault();
                const data = $(this).serialize();
                const url = $(this).data("url");
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status === 'success') {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                                }
                            }).showToast();
                            location.reload(); // Reload to update address info
                        }
                    },
                    error: function(response) {
                        const message = response.responseJSON.message;
                        Toastify({
                            text: message,
                            duration: 3000,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, orange, red)"
                            }
                        }).showToast();
                    }
                });
            });

            // Delete Address
            $("body").on("click", ".delete", function() {
                const url = $(this).data("url");
                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: (response) => {
                                if (response.status === 'success') {
                                    Toastify({
                                        text: response.message,
                                        duration: 3000,
                                        className: "info",
                                        style: {
                                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                                        }
                                    }).showToast();
                                    location
                                        .reload(); // Reload to remove deleted address
                                }
                            }
                        });
                    }
                });
            });

            // Create Address
            $(".add-address").on("submit", function(e) {
                e.preventDefault();
                const data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.address.store') }}",
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status === 'success') {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                                }
                            }).showToast();
                            $(".show-address").addClass("hidden").removeClass("flex");
                            $(".freeze-screen").addClass("hidden");
                            location.reload(); // Reload to show new address
                        }
                    },
                    error: function(response) {
                        const message = response.responseJSON.message;
                        Toastify({
                            text: message,
                            duration: 3000,
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, orange, red)"
                            }
                        }).showToast();
                    }
                });
            });
        });
    </script>
@endpush
