<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Get My Location</title>
</head>
<body>

<h1>My Location</h1>
<p id="output">Waiting for location...</p>

<script>
navigator.geolocation.getCurrentPosition(function(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    fetch('http://127.0.0.1:8000/api/update-location', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': '8|jcRzFjZf8mEEZcIja44TZnCHddNj5iORoMGMbAHD2a70c258' // حط التوكين تبع المستخدم هون
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude
        })
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
});
</script>

</body>
</html>
