function showPathClick()
{	if( validateInpt() == true)
	{	userInpt = $('#userNumber').val();
		$.ajax({
			//url: "http://www.tackypinkflamingos.com/portfolio/four/intToStr.func.php",
			url: "intToStr.func.php",
			type: "post",
			data: {path: userInpt},
			success: function(response)
			{	response = response.replace(/"/g, '');
				// remove all double quotes
				// only need to remove the leading/trailing ones
				// but there shouldn't be any other in response so this is okay
				$('#solnText').html(response);
			}
		});	// ajax call
	}
}

function showSolnClick()
{	if( validateInpt() == true)
	{	userInpt = $('#userNumber').val();
		userConf = confirm("Are you sure you want to see how this works? Once you see it, you can't unsee it");
		if( userConf == true)
		{	$.ajax({
				url: "intToStr.func.php",
				type: "post",
				data: {soln: userInpt},
				success: function(response)
				{	response = response.replace(/"/g, '');
					$('#solnText').html(response);
				}
			});	// end ajax call
		} // end confirm
	} // end validate
}

function validateInpt()
{	var inpt = $('#userNumber').val();
	if( inpt == '')
	{	alert ("Please enter a whole number.");
		return false;
	}
	
	if( $.isNumeric(inpt))
	{	if( Math.floor(inpt) == inpt)
		{	return true;	}
	}
	
	// would have quit out if input was good, so if we're here we know it's bad
	alert(inpt + " is not a whole number. Please enter a whole number.");
	// only shows if inpt is numeric...maybe has to do with the fact that input type is numeric
	return false;
}