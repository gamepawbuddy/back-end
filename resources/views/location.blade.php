<!DOCTYPE html>
<html>

    <head>
        <title>City Location</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <h1>Post City Location</h1>
        <label for="citySelect">Select a City:</label>
        <select id="citySelect">
            <option value="">Select a city</option>
            <!-- Populate the select options from the cities array -->
            <!-- You can also fetch this list from your server if needed -->
            <?php
        $cities = [
            "Bath",
            "Birmingham",
            "Bradford",
            "Brighton & Hove",
            "Bristol",
            "Cambridge",
            "Canterbury",
            "Carlisle",
            "Chelmsford",
            "Chester",
            "Chichester",
            "Colchester",
            "Coventry",
            "Derby",
            "Doncaster",
            "Durham",
            "Ely",
            "Exeter",
            "Gloucester",
            "Hereford",
            "Kingston-upon-Hull",
            "Lancaster",
            "Leeds",
            "Leicester",
            "Lichfield",
            "Lincoln",
            "Liverpool",
            "London",
            "Manchester",
            "Milton Keynes",
            "Newcastle-upon-Tyne",
            "Norwich",
            "Nottingham",
            "Oxford",
            "Peterborough",
            "Plymouth",
            "Portsmouth",
            "Preston",
            "Ripon",
            "Salford",
            "Salisbury",
            "Sheffield",
            "Southampton",
            "Southend-on-Sea",
            "St Albans",
            "Stoke on Trent",
            "Sunderland",
            "Truro",
            "Wakefield",
            "Wells",
            "Westminster",
            "Winchester",
            "Wolverhampton",
            "Worcester",
            "York",
            "Armagh",
            "Bangor",
            "Belfast",
            "Lisburn",
            "Londonderry",
            "Newry",
            "Aberdeen",
            "Dundee",
            "Dunfermline",
            "Edinburgh",
            "Glasgow",
            "Inverness",
            "Perth",
            "Stirling",
            "Bangor (Wales)",
            "Cardiff",
            "Newport",
            "St Asaph",
            "St Davids",
            "Swansea",
            "Wrexham"
        ];

        foreach ($cities as $city) {
            echo "<option value='$city'>$city</option>";
        }
        ?>
        </select>

        <button id="postCity">Post City</button>
        <button id="postLocation">Post Current Location</button>

        <script>
        $(document).ready(function() {
            // API endpoint URL
            var apiUrl = "http://gamepawbuddy.local/api/v1/location";

            // Event listener for the "Post City" button
            $("#postCity").on("click", function() {
                var selectedCity = $("#citySelect").val();

                // Check if a city is selected
                if (selectedCity) {
                    // Make a POST request with the selected city
                    $.post(apiUrl, {
                        city: selectedCity
                    }, function(response) {
                        alert("City posted successfully: " + response);
                    });
                } else {
                    alert("Please select a city.");
                }
            });

            // Event listener for the "Post Current Location" button
            $("#postLocation").on("click", function() {
                // Check if geolocation is available in the browser
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        // Get latitude and longitude
                        var lat = position.coords.latitude;
                        var lon = position.coords.longitude;

                        // Make a POST request with the current location
                        $.post(apiUrl, {
                            latitude: lat,
                            longitude: lon
                        }, function(response) {
                            alert("Location posted successfully: " + response);
                        });
                    }, function(error) {
                        alert("Error getting current location: " + error.message);
                    });
                } else {
                    alert("Geolocation is not available in your browser.");
                }
            });
        });
        </script>
    </body>

</html>