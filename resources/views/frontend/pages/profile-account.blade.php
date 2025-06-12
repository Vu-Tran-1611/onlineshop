@extends('frontend.layout.profile')

@section('profile-content')
    {{-- Profile --}}
    <div>
        <div>
            {{-- Overview --}}
            <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-700 border-slate-300">Overview</h1>
            <div class="my-5 flex flex-col md:flex-row justify-between flex-wrap gap-y-4 ">
                <div class="md:w-[300px]">
                    <h1>Name</h1>
                    <p class="font-semibold">{{ $user->name }}</p>
                </div>

                <div class="w-[300px]">
                    <h1>Username</h1>
                    <p class="font-semibold">{{ $user->username }}</p>
                </div>
                <div class="w-[300px]">
                    <h1>Email</h1>
                    <p class="font-semibold">{{ $user->email }}</p>
                </div>
                <div class="w-[300px]">
                    <h1>Role</h1>
                    <p class="capitalize font-semibold">{{ $user->role }}</p>
                </div>
                <div class="w-[300px]">
                    <h1>Balance</h1>
                    <p class="font-semibold">0</p>
                </div>
                <div class="w-[300px]">
                    <h1>Date Join</h1>
                    <p class="font-semibold">{{ $user->created_at }}</p>
                </div>
            </div>
        </div>
        <div class="flex md:flex-row flex-col md:items-center gap-10 my-5 border-t-2 border-slate-200">
            <div class="flex flex-col md:flex-row md:items-center gap-3 gap-x-8">
                <div class="w-40 h-40 rounded-full overflow-hidden my-4">
                    <img src="{{ asset($user->image) }}" class="w-full h-full object-cover" alt="Avatar" />
                </div>

                <form enctype="multipart/form-data" method="POST" action="{{ route('user.profile.update-profile') }}">
                    @csrf
                    <input name="image" type="file" class="file invisible" /></br>
                    <button class="upload-file button-outline">Edit Avatar</button>
                </form>
            </div>
            <div class="md:h-[100%]  border-l-2 border-slate-200 md:px-8">
                <p>Please select Image that have size less than 5KB</p>
                <p>Do not select offensive image </p>
            </div>
        </div>
        <div class="gap-y-10">
            <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-700 border-slate-300">
                Modify Information
            </h1>
            <div class="mt-5">
                <form enctype="multipart/form-data" method="POST" action="{{ route('user.profile.update-profile') }}"
                    class="flex flex-col gap-y-5">
                    @csrf
                    <input
                        class="md:w-[50%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                        type="text" name="name" placeholder="Name" value="{{ old('name', $user->name) }}" />
                    <input
                        class="md:w-[50%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                        type="text" name="username" placeholder="Username"
                        value="{{ old('username', $user->username) }}" />
                    <input
                        class="md:w-[50%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                        type="text" name="phone" placeholder="Phone" value="{{ old('phone', $user->phone ?? '') }}" />
                    <input
                        class="md:w-[50%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                        type="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" />
                    <button
                        class="w-full md:w-[15%] bg-sky-700 hover:bg-sky-700 text-white font-semibold py-2 rounded-lg transition duration-200"
                        type="submit">
                        Save Change
                    </button>
                </form>
            </div>
        </div>
    </div>


    {{-- Pasword --}}

    <div class="mt-10">
        {{-- Change Password --}}

        <div class="gap-y-10">
            <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-700 border-slate-300">
                Password & Security <br />
                <span class="text-base font-normal">For your safety, We recommend you to use a strong
                    password </span>
            </h1>
            <div
                class="mt-5 flex flex-col md:flex-row md:items-start gap-8 pb-8 border-b-2 border-slate-300 bg-white rounded-lg shadow-sm p-6">
                <div class="flex-1">
                    <form enctype="multipart/form-data" method="POST" action="{{ route('user.profile.update-password') }}"
                        class="flex flex-col gap-y-5">
                        @csrf
                        <label class="font-semibold text-lg mb-2">Modifying Password</label>
                        <ul class="text-base mb-2">
                            @if ($errors->any())
                                @foreach ($errors->all() as $err)
                                    <li class="text-red-600">{{ $err }}</li>
                                @endforeach
                            @endif
                            @if (Session::has('status'))
                                <li class="text-green-600"> {{ session('status') }}!</li>
                            @endif
                        </ul>
                        <input
                            class="md:w-[70%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                            type="password" name="current_password" placeholder="Current Password" />
                        <input
                            class="md:w-[70%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                            type="password" name="password" placeholder="New Password" />
                        <input
                            class="md:w-[70%] w-full rounded-lg border-2 border-slate-300 focus:border-blue-500 px-4 py-2 transition duration-200 outline-none"
                            type="password" name="password_confirmation" placeholder="Confirm New Password" />
                        <button
                            class="w-full md:w-[40%]  bg-sky-700 hover:bg-sky-700 text-white font-semibold py-2 rounded-lg transition duration-200">Save
                            Change</button>
                    </form>
                </div>
                <div class="flex-1 border-t-2 md:border-t-0 md:border-l-2 pt-6 md:pt-0 md:pl-8 border-slate-200">
                    <h1 class="font-semibold text-sky-700 mb-2">Your New Password</h1>
                    <ul class="list-disc list-inside text-slate-600 space-y-1">
                        <li>At least one character and one number</li>
                        <li>More than 8 characters</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
