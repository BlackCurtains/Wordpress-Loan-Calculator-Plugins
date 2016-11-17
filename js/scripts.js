var sliderFormat = document.getElementById('slider-format');
var inputFormat = document.getElementById('loan');

var sliderFormat2 = document.getElementById('slider-format2');
var inputFormat2 = document.getElementById('loanReturnDays');
var startloan = inputFormat.value || 100;
var startdays = inputFormat2.value || 5;


noUiSlider.create(sliderFormat, {
	start: [ startloan ],
	step: 10,
	range: {
		'min': [ 100 ],
		'max': [ 3000 ]
	},
});

sliderFormat.noUiSlider.on('update', function( values, handle ) {
	inputFormat.value = Math.round(values[handle]);
	updateValues();
});

inputFormat.addEventListener('change', function(){
	sliderFormat.noUiSlider.set(this.value);
	updateValues();
});

noUiSlider.create(sliderFormat2, {
	start: [ startdays ],
	step: 1,
	range: {
		'min': [ 5 ],
		'max': [ 30 ]
	},
});

sliderFormat2.noUiSlider.on('update', function( values, handle ) {
	inputFormat2.value = Math.round(values[handle]);
	updateValues();
});

inputFormat2.addEventListener('change', function(){
	sliderFormat2.noUiSlider.set(this.value);
	updateValues();
});

function updateValues(){
	var fee = '10';
	var amount = $("#loan").val();
	var days = $("#loanReturnDays").val().replace(/ Days/g,"");

	var someDate = new Date();
	var dayNames = new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	var monthNames = new Array('January','February','March','April','May','June','July','August','September','October','November','December');

	var numberOfDaysToAdd = +days;
	var nextDate = someDate.setDate(someDate.getDate() + numberOfDaysToAdd);
	var dd = someDate.getDate();
	var mm = someDate.getMonth() + 1;
	var y = someDate.getFullYear();
	var D = dayNames[someDate.getDay()];
	var M = monthNames[someDate.getMonth()];

	var someFormattedDate = D + ' '+ dd + ' '+ M + ' '+ y;
	if(D == "Sunday"){
		// alert('');
		$('#submitbtn').attr('disabled','disabled');
		$( "#loan-error" ).html( "Its Sunday so please select some other day as bank will be closed on Sunday!!" )
											.css("color", "#f1b938");
	}else{
		$('#submitbtn').removeAttr('disabled');
		$( "#loan-error" ).html( "" );
	}

	var fee1 = amount * fee;
	var total = fee1 / 100;
	var total = parseInt(amount) + parseInt(total);
	var total = Math.round(total);

	$("#amount").text('R'+amount);
	$("#interest").text("("+fee+"%)");
	$("#total").text('R'+total);
	$("#totalAmount").val('R'+total);
	$("#days").text('*Repayment Date: '+someFormattedDate);
	$("#day").text(+days);

	$('#days').html($('#days').html().substring(0,42));
	var x = "?loan="+amount+"&days="+days+"&totalAmount="+total+"";
	var y = amount +"/"+ days;

	var result = x;
	// $("a").attr("href", result)

	if(amount <= 1000){ // vars name
			changeColor('white','red');
		}
	else if(amount <= 2000){ // vars name
			changeColor('white','orange');
		}
	else if(amount <= 3000){ // vars name
			changeColor('white','green');
		}

	if(days <= 10){ // vars name
			changeColor2('white','red');
		}
	else if(days <= 20){ // vars name
			changeColor2('white','orange');
		}
	else if(days <= 30){ // vars name
			changeColor2('white','green');
		}

		function changeColor(color1,color2){
			$("#slider-format .noUi-origin").css("background-color",color1);
			$("#slider-format .noUi-base").css("background-color",color2);
		}
		function changeColor2(color1,color2){
			$("#slider-format2 .noUi-origin").css("background-color",color1);
			$("#slider-format2 .noUi-base").css("background-color",color2);
		}
}

updateValues();
