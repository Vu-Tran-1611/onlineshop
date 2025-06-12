{{-- Footer --}}
<footer class="bg-slate-900 text-slate-300 pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between gap-10">
            <!-- Brand & Description -->
            <div class="mb-8 md:mb-0 md:w-1/3">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-slate-700 rounded-full p-2">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6">
                            </path>
                        </svg>
                    </span>
                    <span class="text-2xl font-bold text-white">OShop</span>
                </div>
                <p class="text-slate-400 mb-4">Your one-stop shop for everything online. Discover the latest trends and
                    unbeatable deals.</p>
                <div class="flex gap-3 mt-4">
                    <a href="#" class="hover:text-white" aria-label="Facebook"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-white" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white" aria-label="Instagram"><i
                            class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white" aria-label="LinkedIn"><i
                            class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <!-- Links -->
            <div class="flex flex-1 flex-col sm:flex-row gap-10">
                <div>
                    <h3 class="text-white font-semibold mb-3">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:underline">Home</a></li>
                        <li><a href="#" class="hover:underline">Shop</a></li>
                        <li><a href="#" class="hover:underline">About Us</a></li>
                        <li><a href="#" class="hover:underline">Contact</a></li>
                        <li><a href="#" class="hover:underline">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Customer Service</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:underline">FAQ</a></li>
                        <li><a href="#" class="hover:underline">Returns</a></li>
                        <li><a href="#" class="hover:underline">Shipping</a></li>
                        <li><a href="#" class="hover:underline">Support</a></li>
                        <li><a href="#" class="hover:underline">Order Tracking</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Contact Us</h3>
                    <ul class="space-y-2 text-sm">
                        <li><span class="block">Email: <a href="mailto:support@onlineshop.com"
                                    class="hover:underline">support@onlineshop.com</a></span></li>
                        <li><span class="block">Phone: <a href="tel:+1234567890" class="hover:underline">+1 234 567
                                    890</a></span></li>
                        <li><span class="block">Mon-Fri: 9am - 6pm</span></li>
                        <li><span class="block">123 Market Street, City, Country</span></li>
                    </ul>
                </div>
            </div>
            <!-- Newsletter -->
            <div class="md:w-1/4">
                <h3 class="text-white font-semibold mb-3">Subscribe to our Newsletter</h3>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Your email address"
                        class="rounded px-3 py-2 bg-slate-800 text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white rounded px-3 py-2 font-semibold transition">Subscribe</button>
                </form>
            </div>
        </div>
        <div class="border-t border-slate-700 mt-10 pt-4 text-center text-slate-500 text-xs">
            &copy; {{ date('Y') }} OnlineShop. All rights reserved. | Designed with <span
                class="text-red-500">&hearts;</span>
        </div>
    </div>
</footer>
