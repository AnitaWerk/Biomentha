	<!-- js-->

	<script src="js/jquery-2.2.3.min.js"></script>

	<!-- js-->





	<!-- Banner Responsiveslides -->

	<script src="js/responsiveslides.min.js"></script>

	<script>

		// You can also use "$(window).load(function() {"

		$(function () {

			// Slideshow 4

			$("#slider3").responsiveSlides({

				auto: true,

				pager: true,

				nav: false,

				speed: 500,

				namespace: "callbacks",

				before: function () {

					$('.events').append("<li>before event fired.</li>");

				},

				after: function () {

					$('.events').append("<li>after event fired.</li>");

				}

			});



		});

	</script>

	<!-- // Banner Responsiveslides -->

	<script src="js/smoothbox.jquery2.js"></script>

	<!--pop-up-box -->

	<script src="js/jquery.magnific-popup.js"></script>

	<script>

		$(document).ready(function () {

			$('.popup-with-zoom-anim').magnificPopup({

				type: 'inline',

				fixedContentPos: false,

				fixedBgPos: true,

				overflowY: 'auto',

				closeBtnInside: true,

				preloader: false,

				midClick: true,

				removalDelay: 300,

				mainClass: 'my-mfp-zoom-in'

			});

		});

	</script>

	<!-- //pop-up-box -->

	<!-- start-smooth-scrolling -->

	<script src="js/move-top.js "></script>

	<script src="js/easing.js "></script>

	<script>

		jQuery(document).ready(function ($) {

			$(".scroll ").click(function (event) {

				event.preventDefault();



				$('html,body').animate({

					scrollTop: $(this.hash).offset().top

				}, 1000);

			});

		});

	</script>

	<!-- //end-smooth-scrolling -->

	<!-- smooth-scrolling-of-move-up -->

	<script>

		$(document).ready(function () {

			/*

			 var defaults = {

				 containerID: 'toTop', // fading element id

				 containerHoverID: 'toTopHover', // fading element hover id

				 scrollSpeed: 1200,

				 easingType: 'linear' 

			 };

			 */



			$().UItoTop({

				easingType: 'easeOutQuart'

			});



		});

	</script>

	<!-- Bootstrap Core JavaScript -->

	<script src="js/bootstrap.js "></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="js/script.js "></script>

	<!-- //Bootstrap Core JavaScript -->

	<script>
		function getRandomImage(imgAr, path) {
			path = path || 'images/'; // default path here
			let num = Math.floor( Math.random() * imgAr.length );
			let img = imgAr[num];
			return path + img;
		}

		let images_array = ["88722.jpg"];
		let image = getRandomImage(images_array);
		document.body.style.backgroundImage = "url('" + image + "')";
	</script>

<!-- MERCADOPAGO -->
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script>
window.Mercadopago.setPublishableKey("<?=MP_PUBLIC_KEY?>");

document.getElementById('cardNumber').addEventListener('change', guessPaymentMethod);

function guessPaymentMethod(event) {
   let cardnumber = document.getElementById("cardNumber").value;
   if (cardnumber.length >= 6) {
       let bin = cardnumber.substring(0,6);
       window.Mercadopago.getPaymentMethod({
           "bin": bin
       }, setPaymentMethod);
   }
};

function setPaymentMethod(status, response) {
   if (status == 200) {
       let paymentMethod = response[0];
       document.getElementById('paymentMethodId').value = paymentMethod.id;

       if(paymentMethod.additional_info_needed.includes("issuer_id")){
           getIssuers(paymentMethod.id);
       } else {
           getInstallments(
               paymentMethod.id,
               document.getElementById('transactionAmount').value
           );
       }
   } else {
       alert(`payment method info error: ${response}`);
   }
}

// Banco Emisor
function getIssuers(paymentMethodId) {
   window.Mercadopago.getIssuers(
       paymentMethodId,
       setIssuers
   );
}

function setIssuers(status, response) {
   if (status == 200) {
       let issuerSelect = document.getElementById('issuer');
       response.forEach( issuer => {
           let opt = document.createElement('option');
           opt.text = issuer.name;
           opt.value = issuer.id;
           issuerSelect.appendChild(opt);
       });

       getInstallments(
           document.getElementById('paymentMethodId').value,
           document.getElementById('transactionAmount').value,
           issuerSelect.value
       );
   } else {
       alert(`issuers method info error: ${response}`);
   }
}

// Crea el token de la tarjeta
let doSubmit = false;
document.getElementById('paymentForm').addEventListener('submit', getCardToken);

function getCardToken(event){
    event.preventDefault();
    if(!doSubmit){
        let $form = document.getElementById('paymentForm');
        window.Mercadopago.createToken($form, setCardTokenAndPay);
        return false;
    }
};

function setCardTokenAndPay(status, response) {
    if (status == 200 || status == 201) {
        let button_pay = document.getElementById("button-pay");
        button_pay.value = "Procesando...";
        button_pay.disabled = true;

        let form = document.getElementById('paymentForm');
        let card = document.createElement('input');
        card.setAttribute('name', 'token');
        card.setAttribute('type', 'hidden');
        card.setAttribute('value', response.id);
        form.appendChild(card);
        doSubmit=true;
        form.submit();
    } else {
        //alert("Verify filled data!\n"+JSON.stringify(response, null, 4));
        alert("Verifica todos los datos!!");
    }
};
</script>

</body>
</html>