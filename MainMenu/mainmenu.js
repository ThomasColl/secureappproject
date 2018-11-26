/* Name: Thomas Coll */
/* Student Number: C00204384 */


//Use a confirm to ask user if they want to submit the form
function confirmCheck(form) {
	var response;
	response = confirm("Are you sure these details are correct?");
	
	var arePasswordsTheSame = confirmPassword(form)
	if(response)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function confirmPassword(form) {
	if(form.password == form.cpassword) {
		return true;
	}
	return false;
}

//Used to automatically submit the form, giving an illusiion of instant submission
function forceSubmit() {
	document.myForm.submit();
}