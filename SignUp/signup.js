/* Name: Thomas Coll */
/* Student Number: C00204384 */


//Use a confirm to ask user if they want to submit the form
function confirmCheck(form) {
	var arePasswordsTheSame = confirmPassword(form);

	if(arePasswordsTheSame) {
		var response;
		response = confirm("Are you sure these details are correct?");
			
		if(response) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		alert("The passwords do not match");
		return false;
	}
}

function confirmPassword(form) {
	if(form.elements["password"].value == form.elements["cpassword"].value) {
		return true;
	}
	return false;
}

function isPasswordPowerfulEnough(form) {
	var password = form.elements["password"].value;
	var errors = [];

	if(length(password) < 8) {
		errors.push("The password is too short");
	}
	if(!password.match(/[a-z]/)) {
		errors.push("The password does not feature a lower case letter");
	}
	if(!password.match(/[A-Z]/)) {
		errors.push("The password does not feature an upper case letter");
	}
	if(!password.match(/[0-9]/)) {
		errors.push("The password does not feature a number");
	}

	if(errors) {
		alert(errors);
		return false;
	}
	else {
		return true;
	}

}