# **iut-weather**

# 🖥️ Console Commands

This project includes several custom **Artisan commands** to manage weather updates, logs, and email notifications.

---

## **🔹 1. Fetch Current Weather for a City**
```bash
php artisan weather:fetch {city}
```

📌 Description: Retrieves the current weather for a specified city and displays it in the console.  
📌 Required Parameter: {city} → The name of the city to fetch weather for.

✅ Example Usage:  
```bash
php artisan weather:fetch Paris
```

✅ Example Output:  

```yaml
Fetching weather data for Paris...
City: Paris
Coordinates: Latitude 48.8566, Longitude 2.3522
Temperature: 15°C
Weather: Clear Sky
Humidity: 72%
Wind Speed: 3.5 m/s
```

## **🔹 2. Send Weekly Weather Emails
```bash
php artisan weather:send-weekly-emails
```
📌 Description: Sends weekly weather forecast emails to users who have opted in.  
📌 Behavior:  

Retrieves users with wants_email = true.  
Filters favorite cities based on the user’s forecast_scope.  
Fetches forecasts and generates HTML tables & CSV attachments.  
Sends emails via Laravel’s Mail system.  
Automatically deletes the CSV file after sending.  

✅ Example Output:

```yaml
Fetching users who opted for weekly weather emails...
Processing user: example@email.com
Fetching forecast for city: Paris
Email sent to example@email.com for city: Paris
Weekly weather emails sent successfully.
```

## **🔹 3. Send Weather Forecast Email for a Specific City
```yaml
php artisan weather:email-forecast {city} --email={email}
```

📌 Description: Fetches the weather forecast for a specific city and sends it via email, including a CSV attachment.  
📌 Required Parameter: {city} → The name of the city to fetch weather for.  
📌 Optional Flag: --email={email} → The recipient’s email address (default: the sender’s email in .env).  

✅ Example Usage:
```bash
php artisan weather:email-forecast "Paris" --email=john.doe@example.com
```

✅ Example Output:
```yaml
Fetching weather forecast for Paris...
Weather forecast for Paris has been emailed to john.doe@example.com.
```
✅ Email Contents:  

HTML Table with daily weather forecast.  
CSV Attachment containing weather data.