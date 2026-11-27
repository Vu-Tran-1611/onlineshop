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

<!-- ## 5. System Architecture

(Coming soon) -->

## 5. Screenshots / Demo
1. Login Page
    ![Login Page](./public/screenshots/02-category-page.png)

2. Homepage

    ![Homepage](./public/screenshots/01-homepage.png)


3. Category browsing page
    ![Category Browsing Page](./public/screenshots/10-ai-gift-recommendation.png)


4. Search results page
    ![Product Search Page](./public/screenshots/09-login-page.png)


5. Product details page
    ![Product details page](./public/screenshots/03-search-page.png)


6. Cart page
   ![Cart page](./public/screenshots/04-product-details-page.png)


7. Checkout page

   ![Checkout page](./public/screenshots/06-checkout-page.png)
   ![Checkout page](./public/screenshots/07-order-details-page.png)


8. Order details page
   ![Order Detail Page](./public/screenshots/08-customer-address-page.png)



9. Customer-vendor real-time chat page

    ![Vendor Chat](./public/screenshots/12-ai-chatbot-homepage-support.png)

    ![Vendor Chat](./public/screenshots/13-ai-iphone-recommendation.png)
10. Agentic RAG AI 

    ![Agentic RAG AI](./public/screenshots/14-homepage-hero-banner.png)


  
11. AI shopping assistant response page
    ![AI chatbot product suggestion](./public/screenshots/15-ai-dior-recommendation.png)
    ![AI chatbot product suggestion](./public/screenshots/16-ai-hoodie-recommendation.png)
    ![Customer profile page](./public/screenshots/17-customer-profile-page.png)
    ![AI chatbot product suggestion](./public/screenshots/20-vendor-chat-dashboard-page.png)
    ![AI chatbot product suggestion](./public/screenshots/18-customer-vendor-chat-page.png)
    ![AI chatbot product suggestion](./public/screenshots/19-checkout-payment-method-page.png)


12. Update Account Page
    ![Update Address](./public/screenshots/05-cart-page.png)
    ![Update Account](./public/screenshots/11-ai-watch-recommendation.png)

13. Vendor Manage Product Page
    ![Update Account](./public/screenshots/vendor-product.png)
14. Vendor Create Product Page
    ![Update Account](./public/screenshots/vendor-create-product.png)
15. Vendor Invoice Page
    ![Update Account](./public/screenshots/vendor-invoice.png)
16. Admin Dashboard Page
    ![Update Account](./public/screenshots/admin-dashboard.png)
17. Admin Sub Category Manage Page
    ![Update Account](./public/screenshots/admin-sub-category-manage.png)
18. Admin Category Manage Page
    ![Update Account](./public/screenshots/admin-category-manage.png)
19. Admin Slider Manage Page
    ![Update Account](./public/screenshots/admin-slider-manage.png)
20. Admin Coupon Manage Page
    ![Update Account](./public/screenshots/admin-coupon-manage.png)
21. Admin Flash Sale Manage Page
    ![Update Account](./public/screenshots/admin-flash-sale.png)
16. Admin Brand Manage Page
    ![Update Account](./public/screenshots/admin-brand-manage.png)
16. Admin User Manage Page
    ![Update Account](./public/screenshots/admin-user.png)
## 6. How to Run Locally

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

   # Queue (RabbitMQ)
   QUEUE_CONNECTION=rabbitmq
   RABBITMQ_HOST=localhost
   RABBITMQ_PORT=5672

   # FastAPI services (ml-recommendation-service)
   PYTHON_API_URL=http://127.0.0.1:8000/api
   AI_SERVICE_KEY=same_value_as_ml_service_AI_SERVICE_KEY
   AI_SERVICE_RATE_LIMIT=600

   # Search (Meilisearch). Leave SCOUT_DRIVER unset to fall back to MySQL LIKE search
   SCOUT_DRIVER=meilisearch
   SCOUT_QUEUE=true
   MEILISEARCH_HOST=http://127.0.0.1:7700
   MEILISEARCH_KEY=your_local_master_key
   # Used by Meilisearch to embed products for hybrid (keyword + meaning) search
   OPENAI_API_KEY=your_openai_api_key

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

6. Start RabbitMQ and Meilisearch

   Open Docker Desktop. The `onlineshop_rabbitmq` and `onlineshop_meilisearch` containers start on their own.

   First time only, create the Meilisearch container (data is kept in the `onlineshop_meili_data` volume):

   ```bash
   docker run -d --name onlineshop_meilisearch --restart unless-stopped -p 7700:7700 \
     -v onlineshop_meili_data:/meili_data -e MEILI_MASTER_KEY=your_local_master_key \
     getmeili/meilisearch:v1.58.0
   ```

   Then push the index settings and import the products (the queue worker must be running):

   ```bash
   php artisan scout:sync-index-settings
   php artisan scout:import "App\Models\Product"
   ```

   `scout:sync-index-settings` also sends the OpenAI embedder, and Meilisearch then embeds every product for hybrid search. Run it again after changing `config/scout.php`.

   Product changes are synced to Meilisearch through the queue. After changing PHP code, restart the queue worker so it loads the new code.

7. Start the servers, each in its own terminal

   ```bash
   # Website
   php artisan serve --port=8001

   # AI API for the ML service
   php artisan serve --port=8004

   # Queue worker (emails use the "emails" queue)
   php artisan queue:work rabbitmq --queue=default,emails
   ```

   `php artisan serve` handles one request at a time. The chatbot request on 8001 waits for the ML service, which calls Laravel back for product search, so the ML service needs its own server on 8004 (`LARAVEL_API_URL=http://localhost:8004` in the ML service `.env`).

   Then start the ML service from `ml-recommendation-service`:

   ```bash
   uvicorn app.main:app --reload
   ```

8. Access the application

   - Frontend: http://127.0.0.1:8001
   - Admin panel: http://127.0.0.1:8001/admin
   - Vendor panel: http://127.0.0.1:8001/vendor

## 7. Production (Railway)

| Service | Role |
|---|---|
| `onlineshop` | Website (`$PORT`) and AI API for the ML service (port 8081) |
| `onlineshop-worker` | Queue worker (`php artisan queue:work rabbitmq --queue=default,emails`), restart policy ALWAYS. Variables reference `onlineshop` |
| `Meilisearch` | Search index (`getmeili/meilisearch:v1.58.0`), volume at `/meili_data`, private network only |
| `MySQL`, `RabbitMQ` | Database and queue broker |
| `ecommerce-recommendation-system` | ML service (recommendations and chatbot) |

Search settings on `onlineshop`: `SCOUT_DRIVER=meilisearch`, `SCOUT_QUEUE=true`, `MEILISEARCH_HOST` and `MEILISEARCH_KEY` (references to the Meilisearch service), `OPENAI_API_KEY` (reference to the ML service). Removing `SCOUT_DRIVER` falls back to MySQL search.

Rebuild the search index (the config is cached in the container, so clear it first):

```bash
railway ssh --service onlineshop "php artisan config:clear && SCOUT_QUEUE=false php artisan scout:sync-index-settings && SCOUT_QUEUE=false php artisan scout:import 'App\\Models\\Product'; php artisan config:cache"
```

Production's migration history is missing `create_knowledge_base_documents_table`, so run new migrations with `--path`.

## 8. Future Improvements

- Clickable subcategory badges in the chatbot
- Conversation memory for the chatbot
- Add more login methods (Google, Facebook OAuth)
- Add mobile app support

## 9. License

This project is licensed under the MIT License - see the Laravel framework license for details.
