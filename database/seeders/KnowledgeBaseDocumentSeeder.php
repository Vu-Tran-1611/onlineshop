<?php

namespace Database\Seeders;

use App\Models\KnowledgeBaseDocument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeBaseDocumentSeeder extends Seeder
{
		/**
		 * Seed knowledge base documents.
		 */
		public function run(): void
		{
				$documents = [
						'shipping_policy' => [
								'title' => 'Shipping Policy',
								'content' => $this->shippingPolicyHtml(),
						],
						'return_policy' => [
								'title' => 'Return Policy',
								'content' => $this->returnPolicyHtml(),
						],
						'refund_policy' => [
								'title' => 'Refund Policy',
								'content' => $this->refundPolicyHtml(),
						],
						'warranty_policy' => [
								'title' => 'Warranty Policy',
								'content' => $this->warrantyPolicyHtml(),
						],
						'payment_policy' => [
								'title' => 'Payment Policy',
								'content' => $this->paymentPolicyHtml(),
						],
						'order_cancellation_policy' => [
								'title' => 'Order Cancellation Policy',
								'content' => $this->orderCancellationPolicyHtml(),
						],
						'privacy_policy' => [
								'title' => 'Privacy Policy',
								'content' => $this->privacyPolicyHtml(),
						],
						'terms_and_conditions' => [
								'title' => 'Terms and Conditions',
								'content' => $this->termsAndConditionsHtml(),
						],
						'faq' => [
								'title' => 'Frequently Asked Questions',
								'content' => $this->faqHtml(),
						],
						'contact_support' => [
								'title' => 'Contact and Support',
								'content' => $this->contactSupportHtml(),
						],
						'about_us' => [
								'title' => 'About Us',
								'content' => $this->aboutUsHtml(),
						],
				];

				foreach ($documents as $type => $data) {
						KnowledgeBaseDocument::updateOrCreate(
								['slug' => Str::slug($type)],
								[
										'title' => $data['title'],
										'document_type' => $type,
										'content' => $data['content'],
										'source_url' => null,
										'version' => 1,
								]
						);
				}
		}

		private function shippingPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document shipping-policy">
	<header>
		<h1>Shipping Policy</h1>
		<p>
			Our Shipping Policy explains how orders are processed, shipped, and delivered after a customer completes a purchase on our website.
            Noticed that the processes described in this policy may vary based on the products ordered, customer location, weather conditions, and other factors.
		</p>
	</header>

	<section>
		<h2>Order Processing</h2>
		<p>
			Once an order is successfully placed, our system begins processing it during normal business hours.
			Most orders are processed within 1 to 2 business days, excluding weekends and public holidays.
		</p>
		<p>
			During high-volume seasons, sales events, or product launches, processing time may take slightly longer.
			Customers will receive:
		</p>
		<ul>
			<li>An order confirmation email after placing the order</li>
			<li>A separate shipping confirmation email with tracking details once the order is shipped</li>
		</ul>
	</section>

	<section>
		<h2>Shipping Methods and Delivery Time</h2>
		<p>
			We offer standard shipping for most domestic orders. Standard shipping usually takes 3 to 7 business days
			after the order leaves our warehouse.
		</p>
		<p>Delivery time may vary based on:</p>
		<ul>
			<li>Customer location</li>
			<li>Carrier availability</li>
			<li>Weather conditions</li>
			<li>Other factors outside of our control</li>
		</ul>
		<p>
			Some remote locations may require additional delivery time.
			If a package is delayed after it has been handed to the carrier, customers should first check the tracking link in the shipping confirmation email.
		</p>
	</section>

	<section>
		<h2>Shipping Fees and Free Shipping</h2>
		<p>
			Free standard shipping may be available for orders that meet the minimum purchase requirement shown at checkout.
		</p>
		<p>
			If an order does not qualify for free shipping, the shipping fee is calculated based on:
		</p>
		<ul>
			<li>Delivery address</li>
			<li>Package weight</li>
			<li>Available shipping methods</li>
		</ul>
		<p>
			The final shipping cost is always displayed before the customer confirms the order.
		</p>
	</section>

	<section>
		<h2>Shipping Address Accuracy</h2>
		<p>
			Customers are responsible for entering a complete and accurate shipping address.
			If an incorrect address is provided, the order may be delayed, returned, or delivered to the wrong location.
		</p>
		<p>
			If a customer notices an address mistake shortly after placing an order, they should contact customer support as soon as possible.
			We will try to update the address before shipment, but changes cannot be guaranteed once the order has entered the shipping process.
		</p>
	</section>

	<section>
		<h2>Delivered, Lost, or Stolen Packages</h2>
		<p>
			Once the package is marked as delivered according to the carrier's tracking information, we are not responsible for lost or stolen packages.
		</p>
		<p>
			If tracking shows delivered but the package cannot be located, customers should:
		</p>
		<ul>
			<li>Check around the delivery area</li>
			<li>Ask household members or neighbors</li>
			<li>Contact the shipping carrier directly</li>
		</ul>
		<p>
			Our support team may assist with investigation, but replacement or refund decisions are reviewed case by case.
		</p>
	</section>

	<section>
		<h2>International Shipping and Restrictions</h2>
		<p>
			At this time, international shipping may not be available for all products.
			Some items may have shipping restrictions due to size, weight, brand rules, or destination regulations.
		</p>
		<p>
			If a product cannot be shipped to the selected address, the customer will be notified during checkout or by customer support.
		</p>
	</section>
</article>
HTML;
		}

		private function returnPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document return-policy">
	<header>
		<h1>Return Policy</h1>
		<p>
            For customers who are not completely satisfied with their purchase, our Return Policy explains how to request a return, the return eligibility criteria, and the return process.
            Please read the following return guidelines carefully to ensure a smooth return experience.
        </p>
	</header>

	<section>
		<h2>Return Window and Eligibility</h2>
		<p>
			Customers may request a return within 30 days of the delivery date, unless a different return period is stated on the product page.
		</p>
		<p>
			To be eligible for a return, the item must be unused, undamaged, and in its original packaging with all accessories, manuals, tags, labels, and included materials.
			Items that show signs of heavy use, damage, missing parts, or modification may not be accepted for return.
		</p>
	</section>

	<section>
		<h2>How to Request a Return</h2>
		<p>
			To start a return request, customers should contact our customer support team with:
		</p>
		<ul>
			<li>Order number</li>
			<li>Product name</li>
			<li>Reason for return</li>
			<li>Supporting photos if the product arrived damaged or incorrect</li>
		</ul>
		<p>
			After reviewing the request, our support team will provide return instructions.
			Customers should not send items back without approval, because unauthorized returns may not be processed correctly.
		</p>
	</section>

	<section>
		<h2>Category-Specific Return Restrictions</h2>
		<p>
			Certain product categories may have return restrictions for health, hygiene, or safety reasons.
		</p>
		<p>Examples include:</p>
		<ul>
			<li>Opened cosmetics and skincare items</li>
			<li>Personal care products</li>
			<li>Food-related products</li>
			<li>Intimate items with broken original seals</li>
		</ul>
		<p>
			Electronics such as phones, laptops, tablets, smartwatches, and accessories must be returned with all original components and must not be locked, password-protected, damaged, or altered.
			Clothing, shoes, and fashion items must be unworn, unwashed, and returned with original tags attached.
		</p>
	</section>

	<section>
		<h2>Return Shipping Costs</h2>
		<p>
			If the return is caused by our mistake, such as sending the wrong item or a damaged product, we may provide a prepaid return label or another return solution.
		</p>
		<p>
			If the customer returns an item for personal reasons, such as changing their mind, ordering the wrong size, or no longer wanting the product, the customer may be responsible for return shipping costs.
		</p>
	</section>

	<section>
		<h2>Inspection and Return Outcome</h2>
		<p>
			Returned items will be inspected after they arrive at our return center.
			If the item meets the return requirements, we will approve the return and continue with the refund or exchange process.
		</p>
		<p>
			If the item does not meet our return requirements, we may reject the return and send the item back to the customer.
			In some cases, a partial refund may be issued if the item is returned with missing packaging, minor damage, or signs of use.
		</p>
	</section>

	<section>
		<h2>Tracking and Carrier Responsibility</h2>
		<p>
			We recommend that customers use a trackable shipping method when returning items.
		</p>
		<p>
			We are not responsible for return packages that are lost, damaged, or delivered to the wrong address by the carrier.
			Customers should keep their return tracking number until the return has been fully processed.
		</p>
	</section>
</article>
HTML;
		}

		private function refundPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document refund-policy">
	<header>
		<h1>Refund Policy</h1>
		<p>
			All the refunds are reviewed, approved, and processed after a return, cancellation, or order issue.
            Please note that the refund process may take some time, and the exact timeline depends on various factors such as the payment method, bank processing times, and the reason for the refund.
		</p>
	</header>

	<section>
		<h2>How Refund Processing Works</h2>
		<p>
			Refunds are not issued immediately when a customer requests a return. First, the return request must be approved,
			and the returned product must be received and inspected by our team.
		</p>
		<p>
			Once the returned item passes inspection, we will begin the refund process.
		</p>
	</section>

	<section>
		<h2>Refund Method and Timeline</h2>
		<p>
			Approved refunds are usually processed to the original payment method used during checkout.
		</p>
		<p>
			Depending on the payment provider, bank, or credit card company, it may take 5 to 10 business days for the refund
			to appear on the customer account.
		</p>
		<p>
			In some cases, the refund may appear sooner or later depending on the financial institution.
			We do not control the exact time required by banks or payment processors after the refund has been issued from our side.
		</p>
	</section>

	<section>
		<h2>Shipping Fee Refund Rules</h2>
		<p>
			Shipping fees are generally non-refundable unless the return is caused by an error on our side.
		</p>
		<p>Customers may be eligible for product price and original shipping fee refunds if:</p>
		<ul>
			<li>We shipped the wrong item</li>
			<li>We sent a defective item</li>
			<li>The product was damaged before delivery</li>
		</ul>
		<p>
			If the customer returns an item for personal reasons, such as changing their mind, ordering the wrong item,
			or no longer needing the product, the original shipping fee may not be refunded.
		</p>
	</section>

	<section>
		<h2>Discounts, Coupons, and Partial Item Returns</h2>
		<p>
			If a customer used a coupon, discount code, reward credit, or promotional offer, the refund amount is based on
			the final amount actually paid for the item after discounts.
		</p>
		<p>
			Promotional values may not be refunded as cash.
		</p>
		<p>
			If an order included multiple items and only one item is returned, the refund applies only to the returned item.
			If the return causes the order to no longer meet the requirement for a promotion, free shipping, or bundle discount,
			the final refund amount may be adjusted.
		</p>
	</section>

	<section>
		<h2>Refunds for Canceled Orders</h2>
		<p>
			For canceled orders, refunds depend on the order status.
		</p>
		<ul>
			<li>If the order is canceled before processing or shipment, the refund may be issued quickly.</li>
			<li>If the order has already shipped, the customer may need to wait until the package is returned before a refund can be processed.</li>
		</ul>
	</section>

	<section>
		<h2>Denied or Partial Refunds</h2>
		<p>
			If an item is returned in unacceptable condition, we may deny the refund or issue a partial refund.
		</p>
		<p>Examples include:</p>
		<ul>
			<li>Missing accessories</li>
			<li>Damaged packaging</li>
			<li>Used cosmetics</li>
			<li>Worn clothing</li>
			<li>Locked electronics</li>
			<li>Products returned after the allowed return period</li>
		</ul>
		<p>
			Customers will be notified if a refund is denied or adjusted.
		</p>
	</section>
</article>
HTML;
		}

		private function warrantyPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document warranty-policy">
	<header>
		<h1>Warranty Policy</h1>
		<p>
            For some items, a warranty may be available to provide repair, replacement, or support for manufacturing defects or product issues that occur during normal use.
            But some products may not include warranty coverage, and warranty availability depends on the product type, brand, supplier, and condition.
        </p>
	</header>

	<section>
		<h2>Warranty Availability and Scope</h2>
		<p>
			Some products may include a manufacturer warranty, store warranty, or limited protection period.
			Warranty availability depends on the product type, brand, supplier, and product condition.
		</p>
		<p>
			Customers should review the product page, product packaging, or included documentation to confirm whether
			warranty coverage is available for a specific item.
		</p>
	</section>

	<section>
		<h2>What Is Covered</h2>
		<p>
			For eligible products, warranty coverage usually applies to manufacturing defects or product failures that occur
			during normal use.
		</p>
		<p>A manufacturing defect may include:</p>
		<ul>
			<li>A device not powering on</li>
			<li>A hardware component failing unexpectedly</li>
			<li>A confirmed factory-related issue discovered on arrival</li>
		</ul>
	</section>

	<section>
		<h2>What Is Not Covered</h2>
		<p>
			Warranty coverage does not apply to issues caused by misuse or external damage.
		</p>
		<p>Examples of non-covered conditions include:</p>
		<ul>
			<li>Accidents or improper installation</li>
			<li>Unauthorized repair attempts</li>
			<li>Water damage</li>
			<li>Normal wear and tear</li>
			<li>Cosmetic damage</li>
			<li>Failure to follow product instructions</li>
		</ul>
	</section>

	<section>
		<h2>Electronics Warranty Handling</h2>
		<p>
			Electronics such as phones, laptops, tablets, smartwatches, headphones, and computer accessories may be
			covered by the manufacturer's warranty.
		</p>
		<p>
			In many cases, the customer may need to contact the manufacturer directly for repair, replacement, or technical support.
			Our support team can help guide customers to the correct warranty process, but the final warranty decision may
			be made by the manufacturer.
		</p>
	</section>

	<section>
		<h2>Fashion, Cosmetics, and Consumable Products</h2>
		<p>
			For fashion items, cosmetics, toys, groceries, or personal care products, warranty coverage may be limited or
			unavailable unless the product arrives damaged, defective, or incorrect.
		</p>
		<p>
			Items that are consumed, opened, washed, worn, or used may not qualify for warranty service unless the issue is
			clearly related to a defect.
		</p>
	</section>

	<section>
		<h2>How to Request Warranty Support</h2>
		<p>
			To request warranty support, customers should provide the following details:
		</p>
		<ul>
			<li>Order number</li>
			<li>Product name</li>
			<li>Description of the issue</li>
			<li>Photos or videos showing the problem</li>
			<li>Any troubleshooting steps already attempted</li>
		</ul>
		<p>
			Our team will review the request and determine whether the issue may qualify for warranty support.
			If inspection is required, customers may be asked to send the item back.
		</p>
	</section>

	<section>
		<h2>Possible Warranty Resolutions</h2>
		<p>
			Depending on the product and issue, warranty solutions may include:
		</p>
		<ul>
			<li>Repair</li>
			<li>Replacement</li>
			<li>Store credit</li>
			<li>Refund</li>
			<li>Referral to the manufacturer</li>
		</ul>
		<p>
			We cannot guarantee that every warranty request will be approved.
			Warranty claims submitted after the warranty period has expired may be denied.
		</p>
		<p>
			Customers are encouraged to keep their order confirmation and product packaging during the warranty period
			in case support is needed.
		</p>
	</section>
</article>
HTML;
		}

		private function paymentPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document payment-policy">
	<header>
		<h1>Payment Policy</h1>
		<p>
			Our Payment Policy explains the payment methods, payment security practices, and billing rules used on our website.
		</p>
	</header>

	<section>
		<h2>Accepted Payment Methods</h2>
		<p>
			Customers must provide valid payment information at checkout before an order can be processed.
		</p>
		<p>
			Accepted payment methods may include major credit cards, debit cards, digital wallets, gift cards,
			store credits, or other payment options displayed at checkout.
		</p>
		<p>
			Available payment methods may vary depending on the customer location, order amount, device,
			or payment provider availability.
		</p>
	</section>

	<section>
		<h2>Pricing, Currency, and Order Total</h2>
		<p>
			All prices shown on the website are listed in the currency displayed at checkout.
			Product prices may change at any time due to promotions, inventory updates, supplier costs, or business decisions.
		</p>
		<p>
			The final price, including discounts, shipping fees, and estimated taxes, is shown before the customer confirms the order.
			Customers are responsible for reviewing the order total carefully before completing payment.
		</p>
	</section>

	<section>
		<h2>Payment Authorization and Declines</h2>
		<p>
			Payment must be successfully authorized before an order can move into processing.
			If a payment is declined, the order may not be created or may remain unpaid until a valid payment method is provided.
		</p>
		<p>Common reasons for declined payments include:</p>
		<ul>
			<li>Insufficient funds</li>
			<li>Incorrect card information</li>
			<li>Expired cards</li>
			<li>Billing address mismatch</li>
			<li>Fraud prevention rules</li>
			<li>Bank restrictions</li>
		</ul>
		<p>
			Customers should contact their bank or payment provider if they believe a payment was incorrectly declined.
		</p>
	</section>

	<section>
		<h2>Payment Security</h2>
		<p>
			We take payment security seriously. Payment information is handled through secure payment processing systems.
			We do not store full credit card numbers on our servers.
		</p>
		<p>
			Sensitive payment details are processed by trusted payment providers that follow security standards for online transactions.
			Customers should never send full card numbers, passwords, or sensitive payment information through email,
			chat, or support messages.
		</p>
	</section>

	<section>
		<h2>Coupons, Discounts, Gift Cards, and Store Credit</h2>
		<p>
			If a customer uses a coupon code, discount, gift card, or store credit, the discount is applied only if it meets
			the promotion rules.
		</p>
		<p>Promotions may include limits such as:</p>
		<ul>
			<li>Expiration dates</li>
			<li>Minimum order values</li>
			<li>Product restrictions</li>
			<li>One-time-use limits</li>
		</ul>
		<p>
			Discounts cannot always be combined with other offers unless the checkout page allows it.
		</p>
	</section>

	<section>
		<h2>Fraud Checks and Payment Verification</h2>
		<p>
			In cases of suspected fraud, unauthorized payment activity, or unusual order behavior, we may place the order on hold,
			request additional verification, or cancel the order.
		</p>
		<p>
			If an order is canceled due to payment verification failure, any authorized payment may be voided or refunded
			according to the payment provider timeline.
		</p>
	</section>

	<section>
		<h2>Billing Support</h2>
		<p>
			Customers should contact support if they believe they were charged incorrectly or if they need help understanding a payment issue.
		</p>
	</section>
</article>
HTML;
		}

		private function orderCancellationPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document order-cancellation-policy">
	<header>
		<h1>Order Cancellation Policy</h1>
		<p>
            For customers who need to cancel an order, our Order Cancellation Policy explains the rules, procedures, and timelines for cancellation requests.
        </p>
	</header>

	<section>
		<h2>General Cancellation Rules</h2>
		<p>
			We understand that customers may sometimes place an order by mistake, choose the wrong product,
			enter the wrong address, or change their mind.
		</p>
		<p>
			We will try to help with cancellation requests whenever possible, but cancellation is not guaranteed once an order
			has entered processing or shipping.
		</p>
	</section>

	<section>
		<h2>How to Request Cancellation</h2>
		<p>
			Customers should contact customer support as soon as possible if they want to cancel an order.
		</p>
		<p>The request should include:</p>
		<ul>
			<li>Order number</li>
			<li>Customer name</li>
			<li>Reason for cancellation</li>
		</ul>
		<p>
			If the order has not yet been processed, packed, or shipped, we may be able to cancel it and issue a refund
			to the original payment method.
		</p>
		<p>
			If the order has already been sent to the warehouse, packed, or handed to the shipping carrier,
			cancellation may no longer be possible.
		</p>
	</section>

	<section>
		<h2>Orders Already Shipped</h2>
		<p>
			An order cannot usually be canceled after tracking information has been created or after the package has been shipped.
		</p>
		<p>
			In that case, the customer may need to wait for the package to arrive and then submit a return request if the item
			is eligible for return.
		</p>
		<p>
			Return shipping fees and return restrictions may apply depending on the product type and reason for return.
		</p>
	</section>

	<section>
		<h2>Cancellation by Our Team</h2>
		<p>
			Some orders may be canceled by our team. We may cancel an order in situations such as:
		</p>
		<ul>
			<li>Item is out of stock</li>
			<li>Payment cannot be verified</li>
			<li>Shipping address is invalid</li>
			<li>Fraud risk is detected</li>
			<li>Product was listed with an obvious pricing error</li>
			<li>Order violates our policies</li>
		</ul>
		<p>
			If we cancel an order, the customer will be notified by email, and any eligible payment will be refunded.
		</p>
	</section>

	<section>
		<h2>Order Changes Instead of Cancellation</h2>
		<p>
			If a customer requests changes instead of cancellation, such as updating the address, changing product color,
			changing size, or adding items, we will try to help before the order is processed.
		</p>
		<p>
			However, changes cannot be guaranteed. Once an order begins fulfillment, it may be too late to modify the details.
		</p>
	</section>

	<section>
		<h2>Refund Timeline for Canceled Orders</h2>
		<p>
			Refunds for canceled orders are usually processed back to the original payment method.
		</p>
		<p>
			The time required for the refund to appear depends on the payment provider or bank.
			Customers should allow several business days for the refund to be reflected on their account.
		</p>
	</section>

	<section>
		<h2>How to Avoid Cancellation Issues</h2>
		<p>
			To avoid cancellation issues, customers should carefully review product details, quantity, size, color,
			shipping address, and payment information before placing an order.
		</p>
	</section>
</article>
HTML;
		}

		private function faqHtml(): string
		{
				return <<<'HTML'
<article class="kb-document faq-policy">
	<header>
		<h1>Frequently Asked Questions</h1>
		<p>
			If customers cannot find the answer they need, they may contact our support team for additional help.
		</p>
	</header>

	<section>
		<h2>Do I need an account to shop?</h2>
		<p>
			Customers do not always need an account to browse products, but creating an account can make the shopping experience easier.
		</p>
		<p>With an account, customers can:</p>
		<ul>
            <li>Add products to the cart and save them for later</li>
            <li>Purchase products faster with saved payment and shipping information</li>
			<li>View order history</li>
			<li>Track orders</li>
			<li>Save addresses</li>
			<li>Manage wishlist items</li>
			<li>Receive updates about promotions or recommendations</li>
		</ul>
		<p>
			Some features, such as personalized recommendations or saved carts, may require the customer to be logged in.
		</p>
	</section>

	<section>
		<h2>How do I place an order?</h2>
		<p>
			To place an order, customers can follow these steps:
		</p>
		<ol>
			<li>Add products to the cart</li>
			<li>Review the cart page</li>
			<li>Enter shipping information</li>
			<li>Choose a payment method</li>
			<li>Confirm the order</li>
		</ol>
		<p>
			After the order is placed, an order confirmation email is sent.
			If the customer does not receive a confirmation email, they should check spam or junk folders and confirm
			that the email address was entered correctly.
		</p>
	</section>

	<section>
		<h2>How can I track my order?</h2>
		<p>
			Customers can track an order after it has shipped.
			A tracking link is provided by email when the shipping carrier receives the package.
		</p>
		<p>
			Tracking information may take several hours to update after the label is created.
			If tracking does not update for several days, customers may contact support for assistance.
		</p>
	</section>

	<section>
		<h2>Why is my coupon code not working?</h2>
		<p>
			Coupon codes can usually be entered during checkout.
		</p>
		<p>A coupon may not work if it is:</p>
		<ul>
			<li>Expired</li>
			<li>Misspelled</li>
			<li>Already used</li>
			<li>Not valid for selected products</li>
		</ul>
		<p>
			Some coupons require a minimum order amount or may exclude certain brands or categories.
		</p>
	</section>

	<section>
		<h2>I forgot my password. What should I do?</h2>
		<p>
			If a customer forgets their password, they can use the password reset option on the login page.
			A reset link will be sent to the registered email address.
		</p>
		<p>
			For security reasons, customers should not share their password with anyone.
		</p>
	</section>

	<section>
		<h2>What if an item is out of stock?</h2>
		<p>
			If an item is out of stock, customers may check back later or choose a similar product.
			Restock availability depends on suppliers and demand.
		</p>
		<p>
			Some products may be discontinued and may not return.
		</p>
	</section>

	<section>
		<h2>How do I choose the right product?</h2>
		<p>
			Customers should review product descriptions, images, size information, and specifications before purchasing.
		</p>
		<p>
			If they need help choosing a product, they may contact support or use the website recommendation features
			to discover similar items.
		</p>
	</section>
</article>
HTML;
		}

		private function contactSupportHtml(): string
		{
				return <<<'HTML'
<article class="kb-document contact-support-policy">
	<header>
		<h1>Contact and Support</h1>
		<p>
			We want customers to have a smooth shopping experience, and our support team is available to help with product questions,
			order issues, shipping concerns, returns, refunds, account problems, and general website support.
		</p>
	</header>

	<section>
		<h2>Support Channels and Response Time</h2>
		<p>
			Customers can contact support through the contact form on our website, support email, or available chat feature.
			Support availability may vary depending on business hours, holidays, and request volume.
		</p>
		<p>
			Most support requests are reviewed within 1 to 2 business days. During busy seasons, sales events,
			or holidays, response times may be longer.
		</p>
		<p>
			Customers should avoid submitting multiple requests for the same issue because it may slow down the review process.
		</p>
	</section>

	<section>
		<h2>Direct Contact Information</h2>
		<p>
			If you need immediate help, you can also reach us using the contact details below.
		</p>
		<ul>
			<li>Email: oshop4655@gmail.com</li>
			<li>Phone: +1 234 567 890</li>
			<li>Business Hours: Monday to Friday, 9:00 AM to 6:00 PM</li>
			<li>Address: 123 Market Street, City, Country</li>
		</ul>
	</section>

	<section>
		<h2>Information to Include for Faster Support</h2>
		<p>
			When contacting support, customers should include as much relevant information as possible.
		</p>
		<p>For order-related issues, customers should provide:</p>
		<ul>
			<li>Order number</li>
			<li>Email address used during checkout</li>
			<li>Product name</li>
			<li>A clear explanation of the problem</li>
		</ul>
		<p>
			For damaged, defective, or incorrect items, customers should include photos or videos showing the issue,
			the packaging, shipping label, and product condition.
			This helps our team investigate the problem and provide a solution more quickly.
		</p>
	</section>

	<section>
		<h2>Shipping-Related Questions</h2>
		<p>
			For shipping questions, customers should first check the tracking link provided in the shipping confirmation email.
		</p>
		<p>
			If the package appears delayed, missing, or delivered but not received, customers may contact support.
			Our team may ask the customer to confirm the shipping address and check with household members,
			neighbors, building staff, or the shipping carrier.
		</p>
	</section>

	<section>
		<h2>Return and Refund Questions</h2>
		<p>
			For return or refund questions, customers should review the Return Policy and Refund Policy before contacting support.
		</p>
		<p>
			Support can explain return eligibility, provide return instructions, and check refund status.
			However, final approval may depend on product condition, return window, and inspection results.
		</p>
	</section>

	<section>
		<h2>Account Security and Sensitive Information</h2>
		<p>
			For account-related questions, customers should never share passwords, full payment card numbers,
			or sensitive personal information through email or chat.
		</p>
		<p>
			Our support team will never ask for a customer password.
			If customers suspect unauthorized account access, they should reset their password immediately and contact support.
		</p>
	</section>

	<section>
		<h2>Communication Standards</h2>
		<p>
			We aim to provide respectful, helpful, and professional support.
			Customers are expected to communicate respectfully with our team.
		</p>
		<p>
			Abusive, threatening, or fraudulent behavior may result in limited support access or account review.
		</p>
	</section>
</article>
HTML;
		}

		private function aboutUsHtml(): string
		{
				return <<<'HTML'
<article class="kb-document about-us-policy">
	<header>
		<h1>About Us</h1>
		<p>
			When you shop with us, we want the experience to feel simple, helpful, and trustworthy.
		</p>
	</header>

	<section>
		<h2>Who We Are</h2>
		<p>
			Our store was created to bring together a wide range of everyday products in one convenient place,
			including electronics, mobile phones, computers, watches, fashion items, cosmetics, toys,
			and lifestyle products.
		</p>
		<p>
			Instead of making customers browse through confusing pages or unclear product listings,
			we aim to make product discovery easier through organized categories, clear product details,
			and personalized recommendations.
		</p>
	</section>

	<section>
		<h2>Shopping Experience</h2>
		<p>
			We believe online shopping should be more than just adding items to a cart.
			Customers should be able to compare products, understand prices, check important details,
			and feel confident before placing an order.
		</p>
		<p>
			That is why our website focuses on a clean shopping experience, useful product information,
			and features that help customers find items that match their needs.
			Whether someone is looking for a new phone, a smartwatch, a laptop, a gift,
			or a beauty product, our goal is to make the process easier and more enjoyable.
		</p>
	</section>

	<section>
		<h2>Customer-First Commitment</h2>
		<p>
			Our store is built with a customer-first mindset.
			We care about product quality, fair pricing, reliable service, and clear communication.
		</p>
		<p>
			We want customers to understand what they are buying, how shipping works,
			how returns are handled, and how to contact support when help is needed.
			Every part of the shopping process should feel transparent,
			from browsing products to completing checkout and receiving an order.
		</p>
	</section>

	<section>
		<h2>Technology and Recommendations</h2>
		<p>
			We also use technology to improve the shopping experience.
			Our website may provide personalized product recommendations based on browsing behavior,
			product interactions, or customer preferences.
		</p>
		<p>
			These features are designed to help customers discover relevant products faster.
			For example, if a customer is interested in mobile phones,
			the website may recommend similar phones, accessories, tablets,
			or related electronics.
			These recommendations are meant to support the customer, not pressure them into buying.
		</p>
	</section>

	<section>
		<h2>Continuous Improvement</h2>
		<p>
			As our store grows, we continue working to improve our product selection,
			website features, customer support, and order experience.
			We value customer feedback because it helps us understand what works well
			and what needs improvement.
		</p>
		<p>
			If customers notice an issue, have a question, or want help choosing a product,
			they are encouraged to contact our support team.
		</p>
	</section>

	<section>
		<h2>Our Mission</h2>
		<p>
			Our mission is to create an online store that is practical, modern, and easy to use.
			We want customers to feel comfortable shopping with us and confident that they can find useful products,
			receive clear information, and get support when they need it.
		</p>
	</section>
</article>
HTML;
		}

		private function privacyPolicyHtml(): string
		{
				return <<<'HTML'
<article class="kb-document privacy-policy">
	<header>
		<h1>Privacy Policy</h1>
		<p>
			While you are shopping on our website, certain information may be collected to help us process orders,
			improve the shopping experience, provide customer support, and keep the website secure.
		</p>
	</header>

	<section>
		<h2>Information We Collect</h2>
		<p>
			This information may include details you provide directly, such as your name, email address,
			phone number, shipping address, billing address, and order information.
		</p>
		<p>
			We may also collect basic technical information such as device type, browser type, IP address,
			pages visited, product interactions, and general website activity.
		</p>
	</section>

	<section>
		<h2>How We Use Information</h2>
		<p>
			When you create an account, place an order, contact support, subscribe to updates,
			or use website features such as wishlist, cart, or recommendations,
			we may use your information to provide those services.
		</p>
		<p>
			For example, your shipping address is needed to deliver an order,
			your email address is used to send order confirmations and tracking updates,
			and your product interactions may help us recommend items that are more relevant to your interests.
		</p>
	</section>

	<section>
		<h2>Information Sharing</h2>
		<p>
			We do not sell customers' personal information.
		</p>
		<p>
			Customer information is used for normal business purposes such as order fulfillment, payment processing,
			fraud prevention, customer service, website improvement, and legal compliance.
		</p>
		<p>
			In some situations, we may share necessary information with trusted service providers,
			such as payment processors, shipping carriers, website hosting providers, analytics tools,
			or customer support systems.
			These providers should only use the information needed to perform their services.
		</p>
	</section>

	<section>
		<h2>Payment and Sensitive Data</h2>
		<p>
			Payment information is handled through secure payment processing systems.
			We do not store full credit card numbers on our servers.
		</p>
		<p>
			Customers should never send sensitive payment details, passwords,
			or full card numbers through email, chat, or support messages.
			If we need to verify an order or account issue,
			we will only ask for information necessary to help with the request.
		</p>
	</section>

	<section>
		<h2>Cookies and Similar Technologies</h2>
		<p>
			Cookies and similar technologies may be used to remember login sessions,
			keep items in the cart, understand website usage, improve performance,
			and support personalization.
		</p>
		<p>
			Customers may be able to manage cookies through their browser settings,
			but disabling cookies may affect some website features.
		</p>
	</section>

	<section>
		<h2>Data Protection and Security</h2>
		<p>
			We take reasonable steps to protect customer information from unauthorized access,
			misuse, loss, or disclosure.
		</p>
		<p>
			However, no online system can be guaranteed to be completely secure.
			Customers are responsible for keeping their account login information private and using strong passwords.
		</p>
	</section>

	<section>
		<h2>Customer Rights and Contact</h2>
		<p>
			Customers may contact support if they want to update account information,
			ask questions about privacy, or request help with personal data.
		</p>
		<p>
			Some requests may require identity verification before changes can be made.
			By using our website, customers understand that their information may be used as described to support shopping,
			orders, service, security, and website improvement.
		</p>
	</section>
</article>
HTML;
		}

		private function termsAndConditionsHtml(): string
		{
				return <<<'HTML'
<article class="kb-document terms-and-conditions-policy">
	<header>
		<h1>Terms and Conditions</h1>
		<p>
			When you use our website, browse products, create an account, place an order,
			or interact with any feature on the site, you agree to follow these Terms and Conditions.
		</p>
		<p>
			These terms are designed to explain the basic rules for using the website, purchasing products,
			managing orders, and communicating with our team.
			If you do not agree with these terms, you should not use the website or place an order.
		</p>
	</header>

	<section>
		<h2>Account and Information Responsibilities</h2>
		<p>
			Customers are responsible for providing accurate information when creating an account or completing checkout.
			This includes name, email address, phone number, shipping address, billing details, and payment information.
		</p>
		<p>
			If incorrect information is provided, orders may be delayed, canceled, returned, or delivered to the wrong address.
			Customers are also responsible for keeping their account login information secure
			and should not share passwords with others.
		</p>
	</section>

	<section>
		<h2>Product Information and Pricing</h2>
		<p>
			Product information, including names, descriptions, images, prices, availability,
			and specifications, is provided to help customers make purchasing decisions.
		</p>
		<p>
			We try to keep product information accurate and up to date, but errors may sometimes occur.
			Prices, promotions, inventory, and product details may change without notice.
			If an obvious pricing error, stock issue, or product information mistake occurs,
			we may cancel or adjust an order and notify the customer.
		</p>
	</section>

	<section>
		<h2>Order Confirmation and Cancellation Rules</h2>
		<p>
			Placing an item in the cart does not reserve inventory.
			An order is not confirmed until checkout is completed and payment is successfully authorized.
		</p>
		<p>
			In some cases, orders may be canceled due to payment issues, suspected fraud,
			invalid shipping information, product unavailability, or violation of website rules.
			If an order is canceled and payment was collected,
			an eligible refund will be processed according to our refund procedures.
		</p>
	</section>

	<section>
		<h2>Acceptable Website Use</h2>
		<p>
			Customers agree not to misuse the website.
			This includes attempting to hack, disrupt, scrape, overload, copy, abuse,
			or interfere with the website, its data, or its services.
		</p>
		<p>
			Customers also agree not to use the website for fraudulent purchases,
			unauthorized transactions, false information, abusive communication,
			or activity that violates laws or harms other users.
		</p>
	</section>

	<section>
		<h2>Intellectual Property</h2>
		<p>
			All website content, including product images, text, logos, design, layout,
			and features, may be protected by intellectual property rights.
		</p>
		<p>
			Customers may use the website for personal shopping purposes,
			but they may not copy, reproduce, sell, or misuse website content without permission.
		</p>
	</section>

	<section>
		<h2>Related Policies and Updates</h2>
		<p>
			Returns, refunds, shipping, cancellations, warranties, and support requests
			are handled according to the related policies provided on the website.
		</p>
		<p>
			These terms may be updated from time to time as the website changes.
			Continued use of the website after updates means the customer accepts the revised terms.
		</p>
	</section>
</article>
HTML;
		}

		private function placeholderHtml(string $title): string
		{
				return '<article class="kb-document"><h1>' . e($title) . '</h1><p>Content is being prepared and will be updated soon.</p></article>';
		}
}
