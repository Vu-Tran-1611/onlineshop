# Full-Stack Laravel E-Commerce Shop with Recommendation and AI Shopping Assistant

This project is a full-stack e-commerce platform built with Laravel and MySQL. It supports product browsing, cart, wishlist, checkout, coupon discounts, vendor product uploads, admin management, and real-time customer-vendor chat. The platform also integrates two FastAPI services: a product recommendation system for personalized suggestions and an AI shopping assistant for product search and store-policy support.

## 1. Live Demo

Live application: [https://demo.fashion-shop.uk](https://demo.fashion-shop.uk)

## 2. Key Features

### Customer Features

- Register, log in, log out, forgot and reset password
- Browse products by category, sub-category, brand, shop, or flash sale
- Search products by keyword with filters for price range and product type
- View product detail pages with image gallery, variants, and customer reviews
- Add products to cart with variant selection; update quantities or remove items
- Apply coupon codes (percentage or fixed discount) at cart stage
- Checkout with saved delivery address; pay via Stripe or Cash on Delivery
- Track order status and view order details in profile
- Manage multiple saved delivery addresses with a default address
- Add and remove products from a wishlist
- Submit star ratings (1-5), written reviews, and up to 5 review images per product
- Follow and unfollow vendor shops
- Chat with vendors in real time
- Receive personalized product recommendations based on browsing and purchase behavior
- Ask the AI shopping assistant for product and store-policy support

### Vendor Features

- Manage shop profile: name, banner, contact info, and social media links
- Create and manage products with full details: price, offer price, SKU, descriptions, SEO fields
- Manage product image gallery with multiple uploaded images
- Create and manage product variants (e.g., color, size) and variant items with default selection
- View orders containing the vendor's products and update per-line-item status
- View a dashboard with monthly revenue and order count charts, and order status breakdown
- Chat with customers in real time

### Admin Features

- Manage user accounts: create, edit, delete, activate, or deactivate
- Review and approve or reject vendor-uploaded products
- Manage product status, product type, image gallery, variants, and variant items
- Manage categories, sub-categories, and brands with status and featured toggles
- Manage flash sales: set end dates, add or remove products, toggle item status
- Create and manage coupon codes with discount type, quantity limits, date ranges, and max-use rules
- Manage homepage sliders and top banners with status and display order control
- View all orders and monitor status changes across all vendors
- Update admin profile info, avatar, and password

## 3. AI / ML Integrations

### Recommendation Service

The platform connects to a FastAPI recommendation service to display personalized product suggestions based on tracked user interactions (clicks, wishlist adds, cart adds, and star ratings). Three recommendation models are supported:

1. ComiRec — driven by recent clicks and wishlist activity, shown as "Continue Exploring Your Style"
2. Two-Tower Model — driven by clicks, wishlist, cart, and ratings, shown as "Personalized Recommendations"
3. Bert4Rec — a sequence-aware model that considers the order of user interactions

Recommendation results are cached per user (15-minute TTL) and refreshed when interaction history changes.

### AI Shopping Assistant

The platform connects to a FastAPI AI assistant that uses an LLM, LangChain tool-calling logic, and RAG over store-policy documents to answer product and customer-support questions. The assistant is accessible via a chat widget on the frontend and is backed by a knowledge base of policy documents stored in the database.

### AI Product Search API

A REST endpoint (`/api/ai/products/search`) allows the AI assistant to query products by keyword, category, sub-category, brand, price range, and product type. The endpoint is throttled at 60 requests per minute.

## 4. Tech Stack

- Backend: Laravel, PHP
- Frontend: Blade, Tailwind CSS, jQuery
- Database: MySQL
- Queue: RabbitMQ
- Real-time: Pusher (private channel broadcasting)
- Payment: Stripe (Laravel Cashier)
- AI / ML Services: FastAPI (recommendation models and AI assistant)
- Deployment: Railway

## 5. System Architecture

(Coming soon)

## 6. Screenshots / Demo

1. Homepage

   ![Homepage](./public/screenshots/01-homepage.png)

   ![Homepage hero banner](./public/screenshots/14-homepage-hero-banner.png)

2. Category browsing page

   ![Category browsing page](./public/screenshots/02-category-page.png)

3. Search results page

   ![Search results page](./public/screenshots/03-search-page.png)

4. Product details page

   ![Product details page](./public/screenshots/04-product-details-page.png)

5. Cart page

   ![Cart page](./public/screenshots/05-cart-page.png)

6. Checkout page

   ![Checkout page](./public/screenshots/06-checkout-page.png)

   ![Checkout payment method](./public/screenshots/19-checkout-payment-method-page.png)

7. Order details page

   ![Order details page](./public/screenshots/07-order-details-page.png)

8. Customer profile and address management page

   ![Customer address management page](./public/screenshots/08-customer-address-page.png)

   ![Customer profile page](./public/screenshots/17-customer-profile-page.png)

9. Customer-vendor real-time chat page

   ![Customer-vendor real-time chat page](./public/screenshots/18-customer-vendor-chat-page.png)

10. AI chatbot page

    ![AI chatbot support](./public/screenshots/12-ai-chatbot-homepage-support.png)

    ![AI chatbot product suggestion](./public/screenshots/13-ai-iphone-recommendation.png)

    ![AI chatbot product suggestion](./public/screenshots/15-ai-dior-recommendation.png)

    ![AI chatbot product suggestion](./public/screenshots/16-ai-hoodie-recommendation.png)

11. AI shopping assistant response page

    ![AI shopping assistant response page](./public/screenshots/10-ai-gift-recommendation.png)

    ![AI shopping assistant response page](./public/screenshots/11-ai-watch-recommendation.png)

12. Login page

    ![Login page](./public/screenshots/09-login-page.png)

13. Vendor chat dashboard page

    ![Vendor chat dashboard page](./public/screenshots/20-vendor-chat-dashboard-page.png)

## 7. How to Run Locally

1. Clone the repository

   ```bash
   git clone https://github.com/Vu-Tran-1611/onlineshop.git
   cd onlineshop
   ```

2. Install PHP dependencies

   ```bash
   composer install
   ```

3. Install Node dependencies and build assets

   ```bash
   npm install
   npm run build
   ```

4. Create and configure the environment file

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update `.env` with your database, Stripe, Pusher, mail, and FastAPI service credentials:

   ```bash
   # Database
   DB_HOST=127.0.0.1
   DB_DATABASE=onlineshop
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password

   # Stripe
   STRIPE_KEY=your_stripe_publishable_key
   STRIPE_SECRET=your_stripe_secret_key

   # Pusher
   PUSHER_APP_ID=your_pusher_app_id
   PUSHER_APP_KEY=your_pusher_key
   PUSHER_APP_SECRET=your_pusher_secret
   PUSHER_APP_CLUSTER=your_cluster

   # FastAPI services
   PYTHON_API_URL=http://localhost:8000
   FASTAPI_AI_URL=http://localhost:8001

   # Mail
   MAIL_MAILER=smtp
   MAIL_HOST=your_mail_host
   MAIL_USERNAME=your_mail_username
   MAIL_PASSWORD=your_mail_password
   ```

5. Run database migrations and seeders

   ```bash
   php artisan migrate --seed
   ```

6. Start the development server

   ```bash
   php artisan serve
   ```

7. Start the queue worker

   ```bash
   php artisan queue:work rabbitmq --queue=default
   ```

8. Access the application

   - Frontend: http://localhost:8000
   - Admin panel: http://localhost:8000/admin
   - Vendor panel: http://localhost:8000/vendor

## 8. Future Improvements

- Improve product search with semantic search
- Add more login methods (Google, Facebook OAuth)
- Add mobile app support

## 9. License

This project is licensed under the MIT License - see the Laravel framework license for details.
