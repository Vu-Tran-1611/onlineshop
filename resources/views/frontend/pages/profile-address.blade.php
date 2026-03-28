{{-- filepath: c:\laragon\www\onlineshop\resources\views\frontend\pages\profile-address.blade.php --}}

@extends('frontend.layout.profile')

@section('profile-content')
    {{-- Address Section --}}
    <div class="p-8 bg-gradient-to-br from-sky-950 to-slate-400 rounded-2xl shadow-xl">
        <div class="flex justify-between items-center border-b pb-6 mb-6">
            <h2 class="text-3xl font-extrabold text-white flex items-center gap-2">
                <i class="fa-solid fa-location-dot"></i> My Addresses
            </h2>
            <button class="show-add-address bg-gradient-to-r from-sky-900 to-blue-500 hover:from-sky-700 hover:to-blue-900 text-white rounded-xl py-2 px-6 shadow-lg font-semibold transition-all duration-200">
                <i class="fa-solid fa-plus mr-2"></i> Add New Address
            </button>
        </div>

        <div class="mt-8">
            <div class="addresses grid md:grid-cols-2 gap-8">
                @forelse ($addresses as $addr)
                    <div class="address-item relative flex flex-col gap-4 p-6 bg-white rounded-2xl shadow-lg border border-sky-100 hover:shadow-2xl transition-all duration-200">
                        {{-- Default Badge --}}
                        @if ($addr->is_default)
                            <span class="absolute top-4 right-4 text-xs font-bold text-white bg-gradient-to-r from-sky-500 to-blue-800 px-3 py-1 rounded-full shadow">
                                Default
                            </span>
                        @endif

                        {{-- Address Information --}}
                        <div>
                            <h3 class="text-xl font-bold text-sky-700 flex items-center gap-2">
                                <i class="fa-solid fa-user"></i> {{ $addr->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                <i class="fa-solid fa-phone"></i> {{ $addr->phone }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <i class="fa-solid fa-map-pin"></i> {{ $addr->address }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <i class="fa-solid fa-earth-americas"></i>
                                {{ $addr->country }}, {{ $addr->state }} State, {{ $addr->city }} City, {{ $addr->zip }}
                            </p>
                            <span class="inline-block mt-2 text-xs font-medium text-white bg-sky-800 px-2 py-1 rounded">
                                {{ ucfirst($addr->type) }}
                            </span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 mt-4">
                            <button data-id="{{ $addr->id }}" class="edit-address flex items-center gap-1 text-sky-900 hover:text-sky-800 font-semibold transition">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </button>
                            <button data-id="{{ $addr->id }}" class="delete-address flex items-center gap-1 text-red-900 hover:text-red-800 font-semibold transition">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                            @if (!$addr->is_default)
                                <button data-id="{{ $addr->id }}" class="set-default flex items-center gap-1 py-1 px-3 rounded-lg text-sm font-medium bg-gradient-to-r from-sky-900 to-blue-500 text-white hover:from-sky-700 hover:to-blue-900">
                                    <i class="fa-solid fa-star"></i> Set Default
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12">
                        <div class="text-white text-lg">
                            <i class="fa-solid fa-location-slash text-4xl mb-4"></i>
                            <p class="font-semibold">No addresses found</p>
                            <p class="text-sm">Add your first address to get started</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($addresses->hasPages())
                <div class="mt-8 col-span-2">
                    {{ $addresses->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Address Modals --}}
    @foreach ($addresses as $addr)
        <div class="edit-modal-{{ $addr->id }} hidden fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative m-4">
                <button class="close-edit-modal absolute top-4 right-4 text-gray-800 hover:text-gray-700 text-xl cursor-pointer transition-colors">&times;</button>
                <h2 class="text-2xl font-bold mb-6 text-sky-700 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Address
                </h2>
                <form class="update-address-form space-y-5" data-id="{{ $addr->id }}">
                    {{-- Name and Phone --}}
                    <div class="flex gap-4">
                        <input name="name" value="{{ $addr->name }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Full Name" required />
                        <input name="phone" value="{{ $addr->phone }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Phone Number" required />
                    </div>

                    {{-- Country and State --}}
                    <div class="flex gap-4">
                        <input name="country" value="{{ $addr->country }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Country" required />
                        <input name="state" value="{{ $addr->state }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="State" required />
                    </div>

                    {{-- City and Zip --}}
                    <div class="flex gap-4">
                        <input name="city" value="{{ $addr->city }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="City" required />
                        <input name="zip" value="{{ $addr->zip }}" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Zip Code" required />
                    </div>

                    {{-- Address --}}
                    <input name="address" value="{{ $addr->address }}" class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Address" required />

                    {{-- Type Selection --}}
                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700">Label As:</label>
                        <select name="type" class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all">
                            <option value="home" {{ $addr->type == 'home' ? 'selected' : '' }}>Home</option>
                            <option value="work" {{ $addr->type == 'work' ? 'selected' : '' }}>Work</option>
                        </select>
                    </div>

                    {{-- Default Checkbox --}}
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_default" value="true" {{ $addr->is_default ? 'checked' : '' }} class="accent-sky-900" />
                        <label class="text-gray-700">Set As Default Address</label>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end gap-4 pt-2">
                        <button type="button" class="close-edit-modal py-2 px-6 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold transition-colors">Cancel</button>
                        <button type="submit" class="py-2 px-6 bg-gradient-to-r from-sky-900 to-blue-500 text-white hover:from-sky-700 hover:to-blue-900 rounded-lg font-semibold shadow transition-all">
                            <i class="fa-solid fa-check mr-1"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Add New Address Modal --}}
        <div class="add-address-modal hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-lg relative m-4">
            <button class="close-add-modal absolute top-4 right-4 text-gray-800 hover:text-gray-700 text-xl cursor-pointer transition-colors">&times;</button>
                        <h2 class="text-2xl font-bold mb-6 text-sky-700 flex items-center gap-2">
                <i class="fa-solid fa-location-dot"></i> New Address
            </h2>
            <form class="add-address-form space-y-5">
                {{-- Name and Phone --}}
                <div class="flex gap-4">
                    <input name="name" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Full Name" required />
                    <input name="phone" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Phone Number" required />
                </div>

                {{-- Country and State --}}
                <div class="flex gap-4">
                    <input name="country" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Country" required />
                    <input name="state" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="State" required />
                </div>

                {{-- City and Zip --}}
                <div class="flex gap-4">
                    <input name="city" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="City" required />
                    <input name="zip" class="flex-1 p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Zip Code" required />
                </div>

                {{-- Address --}}
                <input name="address" class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all" type="text" placeholder="Address" required />

                {{-- Type Selection --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Label As:</label>
                    <select name="type" class="w-full p-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-800 focus:outline-none transition-all">
                        <option value="home">Home</option>
                        <option value="work">Work</option>
                    </select>
                </div>

                {{-- Default Checkbox --}}
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_default" value="true" class="accent-sky-900" />
                    <label class="text-gray-700">Set As Default Address</label>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-4 pt-2">
                    <button type="button" class="close-add-modal py-2 px-6 bg-gray-200 hover:bg-gray-300 rounded-lg font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="py-2 px-6 bg-gradient-to-r from-sky-900 to-blue-500 text-white hover:from-sky-700 hover:to-blue-900 rounded-lg font-semibold shadow transition-all">
                        <i class="fa-solid fa-check mr-1"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Background Overlay --}}
    <div class="modal-backdrop hidden fixed inset-0 bg-black bg-opacity-40 z-40"></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Optimized Modal Management
    const ModalManager = {
        show: function(selector) {
            $(selector).removeClass('hidden').addClass('flex');
            $('.modal-backdrop').removeClass('hidden');
            $('body').addClass('overflow-hidden');
        },

        hide: function() {
            $('.add-address-modal, [class*="edit-modal-"]').addClass('hidden').removeClass('flex');
            $('.modal-backdrop').addClass('hidden');
            $('body').removeClass('overflow-hidden');
        }
    };

    // AJAX Helper
    const AjaxHelper = {
        request: function(options) {
            const defaults = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'An error occurred';
                    this.showToast(message, 'error');
                }.bind(this)
            };

            return $.ajax($.extend({}, defaults, options));
        },

        showToast: function(message, type = 'success') {
            const background = type === 'success'
                ? "linear-gradient(to right, #00b09b, #96c93d)"
                : "linear-gradient(to right, #ff5f6d, #ffc371)";

            Toastify({
                text: message,
                duration: 3000,
                style: { background }
            }).showToast();
        }
    };

    // Show Add Address Modal
    $(document).on('click', '.show-add-address', function() {
        ModalManager.show('.add-address-modal');
    });

    // Show Edit Address Modal
    $(document).on('click', '.edit-address', function() {
        const id = $(this).data('id');
        ModalManager.show(`.edit-modal-${id}`);
    });

    // Hide All Modals
    $(document).on('click', '.close-add-modal, .close-edit-modal, .modal-backdrop', function(e) {
        e.preventDefault();
        ModalManager.hide();
    });

    // Prevent modal close when clicking inside modal content
    $(document).on('click', '.bg-white', function(e) {
        e.stopPropagation();
    });

    // Add New Address
    $(document).on('submit', '.add-address-form', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        AjaxHelper.request({
            url: "{{ route('user.address.store') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    AjaxHelper.showToast(response.message);
                    ModalManager.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });

    // Update Address
    $(document).on('submit', '.update-address-form', function(e) {
        e.preventDefault();

        const form = $(this);
        const id = form.data('id');
        const formData = form.serialize();

        AjaxHelper.request({
            url: "{{ route('user.address.update', '') }}/" + id,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    AjaxHelper.showToast(response.message);
                    ModalManager.hide();
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });

    // Set Default Address
    $(document).on('click', '.set-default', function() {
        const id = $(this).data('id');

        AjaxHelper.request({
            url: `/user/address/${id}/set-default`,
            method: 'PUT',
            success: function(response) {
                if (response.status === 'success') {
                    AjaxHelper.showToast(response.message);
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });

    // Delete Address
    $(document).on('click', '.delete-address', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                AjaxHelper.request({
                    url: "{{ route('user.address.destroy', '') }}/" + id,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            AjaxHelper.showToast(response.message);
                            setTimeout(() => location.reload(), 1000);
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
