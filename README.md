# **iut-weather**

# 🌤️ **Weather API & User Management**

This project provides an API to **retrieve weather data**, **manage users' favorite locations**, and **configure email preferences**.

## 🚀 **Start the Server**
Before using the API, make sure your Laravel server is running:

```bash
php artisan serve
```

## 🌦️ Weather API
### Get the current weather for a city

GET /api/v1/weather?place=Paris

📌 Description: Returns the current weather for the specified city.
📌 Required parameter: place (city name).

### Get the weather forecast for a city

GET /api/v1/weather/forecast?place=Paris

📌 Description: Returns the weather forecast for the upcoming days for the specified city.
📌 Required parameter: place (city name).

## ⭐ Favorite Locations API
### Retrieve a user's favorite locations

GET /v1/users/{userId}/places

📌 Description: Returns the favorite locations of the specified user by ID.
📌 Example: GET /api/v1/users/2/places

### Add a favorite location for a user

POST /api/v1/users/2/places

📌 Description: Adds a favorite location for the specified user by ID.
📌 Required Body (JSON):

```json
{
    "name": "Tokyo",
    "country": "Japan"
}
```

### Remove a favorite location

DELETE /api/v1/users/2/places/7

📌 Description: Removes the favorite location specified by city ID for the specified user ID.
📌 Example : DELETE /api/v1/users/2/places/7

### Toggle a location between favorite and non-favorite

PATCH /api/v1/users/2/places/4/favorite

📌 Description: Modifies whether the specified city ID is a favorite for the specified user ID.

## 📩 Email Management API
### Enable/Disable email notifications for a user

PATCH /api/v1/users/2/toggle-email

📌 Description: Enables or disables email notifications for the specified user ID.

### Modify weather forecast email preferences

PATCH /api/v1/users/2/update-forecast-scope

📌 Description: Allows the user to choose which weather forecasts they want to receive.
📌 Required Body (JSON):
```json
{
    "forecast_scope": "none"
}
```

📌 Possible values for forecast_scope:

"none" → Receives no forecasts.
"all" → Receives all forecasts.
"favorites" → Receives forecasts only for favorite locations.