// https://api.ciptakerja.id

async function loadVa()
{
    var request = await fetch(vaJs.api_base_url + '/api/v1/virtual-accounts/create',{
        method:'POST',
        headers:{
            'content-type':'application/x-www-form-urlencoded',
            // 'Authorization': 'Bearer '+vaJs.token
        },
        body:buildQuery(vaJs)
    })
    
    if(request.ok)
    {
        var vaWrapper = document.querySelector('#va-wrapper')
        var response = await request.json()
        if(response.data)
        {
            vaWrapper.innerHTML = "<center>No. VA Bank BTN "+response.data.va+"</center>"
        }
    }
    else
    {
        var response = await request.text()
        console.log(response)
    }
}

function buildQuery (data) {

	// If the data is already a string, return it as-is
	if (typeof (data) === 'string') return data;

	// Create a query array to hold the key/value pairs
	var query = [];

	// Loop through the data object
	for (var key in data) {
		if (data.hasOwnProperty(key)) {

			// Encode each key and value, concatenate them into a string, and push them to the array
			query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
		}
	}

	// Join each item in the array with a `&` and return the resulting string
	return query.join('&');

}

loadVa()