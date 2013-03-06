/* custom javascript */

function formsubmit()
{
	document.getElementById("pagecontent").value = document.getElementById("content").value;
	document.getElementById("contentform").submit();
}

function formsubmitsecond()
{
	var checkboxes = document.getElementsByName('pagelist[]');
	for(var i=0; i < checkboxes.length; i++)
	{
		if(checkboxes[i].checked==true)
		{
			return true;
		}
	}
	alert("Please select a value");
	return false;
}

function editformsubmit()
{
	document.getElementById("pagecntnt").value = document.getElementById("content").value;
	document.getElementById("editcontentform").submit();
}