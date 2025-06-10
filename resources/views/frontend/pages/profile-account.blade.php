@extends('frontend.layout.profile')

@section('profile-content')
    <div>
        <div>
            {{-- Overview --}}
            <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-600 border-slate-300">Overview</h1>
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
                <img class="rounded-full" src="{{ asset($user->image) }}" alt="avatar" width="200" />
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
            <h1 class="text-2xl pb-2 font-semibold border-b-2 border-slate-300">Modify Information</h1>
            <div class="mt-5">
                <form enctype="multipart/form-data" method="POST" action="{{ route('user.profile.update-profile') }}"
                    class="flex flex-col gap-y-5">
                    @csrf
                    <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]" type="text"
                        name="name" placeholder="Name" />
                    <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]" type="text"
                        name="username" placeholder="Username" />
                    <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]" type="text"
                        name="phone" placeholder="Phone" />
                    <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]" type="text"
                        name="email" placeholder="Email" />
                    <button class="w-[100%] md:w-[15%] button-outline">Save Change</button>
                </form>
            </div>
        </div>
    </div>
@endsection
