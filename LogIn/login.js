/* Name: Thomas Coll */
/* Student Number: C00204384 */


//Use a confirm to ask user if they want to submit the form
function confirmCheck(form) {
	var response;
	response = confirm("Are you sure these details are correct?");
	
	if(response)
	{
		return true;
	}
	else
	{
		return false;
	}
}
