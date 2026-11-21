# Graph Report - onlineshop  (2026-11-20)

## Corpus Check
- Large corpus: 4869 files · ~3,073,335 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 1417 nodes · 2397 edges · 277 communities (25 shown, 252 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 18 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Events, Mail Jobs & Messaging
- Cart & Chatbot Controllers
- Flash Sale & Knowledge Base Models
- Database Seeders & Policies
- Helpers & Profile/Chat
- Storefront Home Controller
- User Address Management
- Checkout, Profile & Wishlist
- Vendor Shop & Chat
- Category & User Admin
- Brand & Category Models
- Order Management
- Breeze Profile & Login Requests
- Cashier & Cache Migrations
- Vendor Product DataTable
- Admin Profile & Image Uploads
- Email Verification
- Product Model Relations
- Brand DataTable
- PDF Policies & API Routes
- Product Gallery & Reviews
- App & Broadcast Providers
- Homepage Sections & Recommenders
- Category DataTable
- Password Reset & Layouts
- Middleware (Visitor, Role)
- Shop Profile Analytics
- Core Table Migrations A
- Core Table Migrations B
- Coupon DataTable
- Flash Sale Item DataTable
- Slider DataTable
- Top Banners DataTable
- Login Session & Cart Model
- Product Search API
- New Password Flow
- Coupons & Interaction Tracking
- Orders DataTable
- Brand Admin Controller
- Stripe Payment
- SubCategory Admin
- Product Admin
- Product Variant Admin
- Variant Item Admin
- Shop Profile Admin
- User DataTable
- Coupon Admin
- Order CRUD Stub
- Image Gallery Admin
- Product Variant Models
- Event Service Provider
- Product DataTable Class
- Gallery DataTable
- Variant DataTable
- Variant Item DataTable
- Product Approval Admin
- Slider Admin
- Top Banner Admin
- User Factory
- Console Kernel
- SubCategory DataTable
- Delete Guard Helper
- Password Confirmation
- User Registration
- Flash Sale Admin
- Product Detail Page
- Order Products DataTable
- Top Banner Model
- SubCategory Model
- Storefront Master Layout
- Authenticate
- TrustHosts
- AuthServiceProvider
- create users table
- create subscriptions table
- create failed jobs table
- create sliders table
- create user addresses table
- create brands table
- create categories table
- create shop profiles table
- create products table
- create product image galleries table
- create product variant items table
- create flash sell items table
- brand category
- create coupons table
- create pusher settings table
- create chats table
- create user carts table
- create wishlists table
- create user reviews table
- create orders table
- create order products table
- create top banners table
- create shop followers table
- create user product interactions table
- create knowledge base documents table
- Profile Edit Page
- Shop Page
- Admin Layout
- Http / Kernel
- EncryptCookies
- PreventRequestsDuringMaintenance
- TrimStrings
- TrustProxies
- ValidateSignature
- VerifyCsrfToken
- Cart Page
- pages / category
- more-products
- product-by-search
- profile-wishlist
- Vendor Layout
- layouts.navigation
- brand / create
- brand / edit
- brand / index
- category / create
- category / edit
- category / index
- coupons / create
- coupons / edit
- coupons / index
- flash-sell / index
- product-from-vendor / index
- admin / profile / index
- admin / slider / create
- admin / slider / edit
- admin / slider / index
- subcategory / create
- subcategory / edit
- subcategory / index
- topbanner / create
- topbanner / edit
- topbanner / index
- user / index
- order-notification-layout
- order-cancelled
- order-delivered
- order-pending
- order-verified
- password-updated
- emails / welcome
- mastercart
- layout / profile
- about-us
- check-out
- contact-support
- faq
- not-found
- order-cancellation-policy
- payment-cancel
- payment-policy
- payment-success
- privacy-policy
- profile-account
- profile-address
- pages / profile
- profile-order-details
- profile-orders
- refund-policy
- return-policy
- shipping-policy
- terms-and-conditions
- warranty-policy
- filtered-product-list
- filtered-product-slide
- bert4rec-recommended-for-you
- best
- comirec-recommended-for-you
- featured-product
- flash-sell
- light-gcn-recommended-for-you
- matrix-factorization-recommended-for-you
- new-arrival
- sasrec-recommended-for-you
- top-product
- two-tower-recommended-for-you
- chat-pannel / chat
- vendor / dashboard
- order / index
- show
- product / create
- variant / create
- item / create
- vendor / profile / index
- shop-profile / index
- vendor / slider / create
- vendor / slider / edit
- vendor / slider / index

## God Nodes (most connected - your core abstractions)
1. `Controller` - 97 edges
2. `Product` - 32 edges
3. `UploadTrait` - 27 edges
4. `User` - 26 edges
5. `Category` - 23 edges
6. `ShopProfile` - 20 edges
7. `Order` - 15 edges
8. `KnowledgeBaseDocumentSeeder` - 15 edges
9. `ProductController` - 14 edges
10. `Brand` - 14 edges

## Surprising Connections (you probably didn't know these)
- `BrandController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/BrandController.php → app/Http/Controllers/Controller.php
- `BrandController` --mixes_in--> `UploadTrait`  [EXTRACTED]
  app/Http/Controllers/Admin/BrandController.php → app/Traits/UploadTrait.php
- `CategoryController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/CategoryController.php → app/Http/Controllers/Controller.php
- `CategoryController` --mixes_in--> `UploadTrait`  [EXTRACTED]
  app/Http/Controllers/Admin/CategoryController.php → app/Traits/UploadTrait.php
- `OrderController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/OrderController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (277 total, 252 thin omitted)

### Community 0 - "Events, Mail Jobs & Messaging"
Cohesion: 0.08
Nodes (9): MessageEvent, SendOrderNotificationMailJob, SendPawwordUpdatedMailJob, SendWelcomeEmailJob, StoreProductInteractionJob, TrainALSRecommendationModelJob, OrderNotificationMail, PasswordUpdatedMail (+1 more)

### Community 2 - "Cart & Chatbot Controllers"
Cohesion: 0.07
Nodes (9): {closure#1}(), Handler, BotChatController, CartController, {closure#4}(), {closure#6}(), {closure#8}(), PersonalizedRecommendationController (+1 more)

### Community 3 - "Flash Sale & Knowledge Base Models"
Cohesion: 0.10
Nodes (11): BrandCategory, FlashSell, FlashSellItem, KnowledgeBaseDocument, OrderProduct, PusherSetting, ShopFollowers, Slider (+3 more)

### Community 4 - "Database Seeders & Policies"
Cohesion: 0.09
Nodes (5): DatabaseSeeder, KnowledgeBaseDocumentSeeder, UserProductInteractionSeeder, UserReviewSeeder, UserSeeder

### Community 6 - "Helpers & Profile/Chat"
Cohesion: 0.09
Nodes (3): Chat, User, UserAddresses

### Community 7 - "Storefront Home Controller"
Cohesion: 0.14
Nodes (5): HomeController, UserMessageController, ProfileController, {closure#1}(), {closure#2}()

### Community 9 - "Checkout, Profile & Wishlist"
Cohesion: 0.10
Nodes (3): CheckOutController, ProfileController, WishlistController

### Community 10 - "Vendor Shop & Chat"
Cohesion: 0.14
Nodes (6): ProductFromVendorController, Controller, ChatController, ShopFollowController, ChatController, DashboardController

### Community 11 - "Category & User Admin"
Cohesion: 0.11
Nodes (3): CategoryController, UserController, {closure#1}()

### Community 13 - "Order Management"
Cohesion: 0.15
Nodes (3): OrderController, OrderController, Order

### Community 15 - "Cashier & Cache Migrations"
Cohesion: 0.17
Nodes (6): {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#2}()

### Community 17 - "Admin Profile & Image Uploads"
Cohesion: 0.15
Nodes (3): ProfileController, UserReviewsController, UploadTrait

### Community 18 - "Email Verification"
Cohesion: 0.19
Nodes (4): EmailVerificationNotificationController, EmailVerificationPromptController, VerifyEmailController, RouteServiceProvider

### Community 19 - "Product Model Relations"
Cohesion: 0.13
Nodes (3): {closure#3}(), Product, {closure#9}()

### Community 21 - "PDF Policies & API Routes"
Cohesion: 0.15
Nodes (3): PdfController, {closure#1}(), GET /user()

### Community 22 - "Product Gallery & Reviews"
Cohesion: 0.15
Nodes (4): {closure#1}(), {closure#2}(), ProductImageGallery, UserReviews

### Community 24 - "Homepage Sections & Recommenders"
Cohesion: 0.14
Nodes (13): frontend.sections.best, frontend.sections.brand, frontend.sections.category, frontend.sections.featured-product, frontend.sections.flash-sell, frontend.sections.light-gcn-recommended-for-you, frontend.sections.matrix-factorization-recommended-for-you, frontend.sections.new-arrival (+5 more)

### Community 26 - "Password Reset & Layouts"
Cohesion: 0.23
Nodes (3): PasswordResetLinkController, AppLayout, GuestLayout

### Community 27 - "Middleware (Visitor, Role)"
Cohesion: 0.23
Nodes (3): LogVisitor, RedirectIfAuthenticated, Role

### Community 30 - "Core Table Migrations A"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 31 - "Core Table Migrations B"
Cohesion: 0.15
Nodes (3): {closure#1}(), {closure#1}(), {closure#1}()

### Community 68 - "Product Detail Page"
Cohesion: 0.33
Nodes (5): frontend.sections.comirec-recommended-for-you, frontend.layout.master, frontend.partials.filtered-product-list, frontend.partials.product-card, frontend.partials.shop-information

### Community 73 - "Storefront Master Layout"
Cohesion: 0.40
Nodes (4): frontend.layout.navbar, frontend.sections.chat, frontend.sections.chat_bot, frontend.layout.footer

### Community 104 - "Profile Edit Page"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

### Community 105 - "Shop Page"
Cohesion: 0.50
Nodes (3): frontend.layout.master, frontend.partials.filtered-product-list, frontend.partials.shop-information

## Knowledge Gaps
- **119 isolated node(s):** `admin.layout.master`, `admin.layout.master`, `admin.layout.master`, `admin.layout.master`, `admin.layout.master` (+114 more)
  These have ≤1 connection - possible missing edges. (Counts symbols only; 706 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **252 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Vendor Shop & Chat` to `Storefront Home Routes`, `Cart & Chatbot Controllers`, `Flash Sale & Knowledge Base Models`, `Helpers & Profile/Chat`, `Storefront Home Controller`, `User Address Management`, `Checkout, Profile & Wishlist`, `Category & User Admin`, `Brand & Category Models`, `Order Management`, `Breeze Profile & Login Requests`, `Admin Profile & Image Uploads`, `Email Verification`, `PDF Policies & API Routes`, `Product Gallery & Reviews`, `Category DataTable`, `Password Reset & Layouts`, `Login Session & Cart Model`, `Product Search API`, `New Password Flow`, `Coupons & Interaction Tracking`, `Brand Admin Controller`, `Stripe Payment`, `SubCategory Admin`, `Product Admin`, `Product Variant Admin`, `Variant Item Admin`, `Shop Profile Admin`, `User DataTable`, `Coupon Admin`, `Order CRUD Stub`, `Image Gallery Admin`, `Product Variant Models`, `Product DataTable Class`, `Product Approval Admin`, `Slider Admin`, `Top Banner Admin`, `Password Confirmation`, `User Registration`, `Flash Sale Admin`, `Top Banner Model`?**
  _High betweenness centrality (0.110) - this node is a cross-community bridge._
- **What connects `admin.layout.master`, `admin.layout.master`, `admin.layout.master` to the rest of the system?**
  _119 weakly-connected nodes found - possible missing edges._
- **Should `Events, Mail Jobs & Messaging` be split into smaller, more focused modules?**
  _Cohesion score 0.08287961282516637 - nodes in this community are weakly interconnected._
- **Why does `User` connect `Helpers & Profile/Chat` to `User Registration`, `Flash Sale & Knowledge Base Models`, `Database Seeders & Policies`, `Product DataTables`, `New Password Flow`, `Storefront Home Controller`, `Cart & Chatbot Controllers`, `Vendor Shop & Chat`, `Breeze Profile & Login Requests`, `User DataTable`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Should `Storefront Home Routes` be split into smaller, more focused modules?**
  _Cohesion score 0.045454545454545456 - nodes in this community are weakly interconnected._
- **Why does `Product` connect `Product Model Relations` to `Storefront Home Routes`, `Cart & Chatbot Controllers`, `Flash Sale & Knowledge Base Models`, `Product DataTables`, `Coupons & Interaction Tracking`, `User Address Management`, `Brand & Category Models`, `Vendor Product DataTable`, `Product Variant Models`, `Product DataTable Class`, `Product Gallery & Reviews`, `Product Search Service Internals`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Should `Cart & Chatbot Controllers` be split into smaller, more focused modules?**
  _Cohesion score 0.06829268292682927 - nodes in this community are weakly interconnected._