function isNumber(event)
{
	var keycode=event.keyCode;
	if(keycode>48 && keycode<57)
		return true;
	else
		return false;
}