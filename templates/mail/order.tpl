<html>
	<head>
		<title>%%SITE_TITLE%%</title>
	</head>
	<body>

		<p>%%SITE_TITLE%% - Your order </p>
		
		<p>
			Order Number: %%ORDER_ID%%<br />
			Date Ordered: %%DATE%%
		</p>

		<p>
			Products<br />
			------------------------------------------------------<br /><br />

			%%PRODUCTS%%

			<br />------------------------------------------------------
		</p>

		<p>
			Sub-Total: %%SUB_TOTAL%%<br />
			Shipping cost: %%SHIPPING_COST%%<br />
			%%TAX%%<br />
			%%COUPON%%<br />
			Total: %%TOTAL%%<br />
			------------------------------------------------------
		</p>
		
		<p>
			Shipping address<br />
			------------------------------------------------------<br />
			%%FIRST_NAME%% %%LAST_NAME%%<br />
			%%COMPANY%%<br />
			%%ADDRESS%%<br />
			%%CITY%%<br />
			%%POST_CODE%%<br />
			%%COUNTRY%%<br />
			%%ZONE%%<br />
		</p>
		
		<p>
			Payment address<br />
			------------------------------------------------------<br />
			%%FIRST_NAME%% %%LAST_NAME%%<br />
			%%COMPANY%%<br />
			%%ADDRESS%%<br />
			%%CITY%%<br />
			%%POST_CODE%%<br />
			%%COUNTRY%%<br />
			%%ZONE%%<br />
		</p>
		
		<p>* Do not respond to this email  *</p>

		<p>
			This is an automatic email sent from our support system.<br />
			Do not respond to this email, you will not receive any response!
		</p>
		
		<p><a href="%%SITE_URL%%" target="_blank">%%SITE_TITLE%%</a></p>
		
	</body>
</html>
