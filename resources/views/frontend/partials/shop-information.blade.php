<div class="bg-white p-7 flex flex-col md:flex-row items-center gap-8 rounded-2xl shadow-lg border border-sky-100">
    <div class="flex items-center gap-6 ">
        <div class="rounded-full overflow-hidden border-4 border-sky-200 shadow w-[100px] h-[100px]">
            <img src="{{ asset($shop->banner) }}" width="100" height="100" class="object-cover w-[100px] h-[100px]" />
        </div>
        <div class="flex flex-col justify-between border-r-2 pr-7 border-sky-100 h-full">
            <span class="text-2xl font-bold text-sky-700">{{ $shop->name }}</span>
            <span class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <i class="fa-solid fa-circle text-green-400 text-[8px]"></i> Online 2 hours ago
            </span>
            <div class="my-4 flex gap-3">
                <button data-id="{{ $shop->user->id }}" data-banner="{{ $shop->banner }}"
                    data-name="{{ $shop->name }}"
                    class="show-chat-pannel flex items-center gap-2 text-sky-600 border border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                    <i class="fa-brands fa-rocketchat"></i> Chat
                </button>
                @if (!$isVisited)
                    <button
                        class="flex items-center gap-2 text-sky-600 border border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                        <i class="fa-solid fa-shop"></i>
                        <a href="{{ route('shop', ['shop' => $shop->slug]) }}">
                            Visit
                        </a>
                    </button>
                @else
                    @if (!$isFollowed)
                        <button
                            class="follow-button flex items-center gap-2 text-sky-600 border  border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                            <i class="fa-regular fa-heart"></i> Follow
                        </button>
                    @else
                        <button
                            class="follow-button flex items-center gap-2 text-sky-600 border  border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                            <i class="fa-solid fa-heart"></i> Unfollow
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-x-10 gap-y-4 w-full md:pl-10">
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-star text-yellow-400"></i> Vote
            </span>
            <span class="text-xl font-bold text-sky-600">94K</span>
        </div>
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-star text-yellow-500"></i> Feedback Rate
            </span>
            <span class="text-xl font-bold text-sky-600">85%</span>
        </div>
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus text-green-600"></i> Join
            </span>
            <span class="text-xl font-bold text-sky-600">{{ $shop->dateJoin() }}</span>
        </div>
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-purple-600"></i> Products
            </span>
            <span class="text-xl font-bold text-sky-600">{{ $shop->productsCount() }}</span>
        </div>
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-clock text-pink-600"></i> Response Time
            </span>
            <span class="text-xl font-bold text-sky-600">In hours</span>
        </div>
        <div class="flex flex-col items-start">
            <span class="text-slate-500 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user-group text-indigo-600"></i> Follower
            </span>
            <span class="text-xl font-bold text-sky-600 followers">{{ $shop->followersCount() }}</span>
        </div>
    </div>
</div>
