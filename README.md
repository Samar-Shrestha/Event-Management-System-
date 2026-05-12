# 🎉 Event Management System — Nepal

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

A full-stack web application for discovering and booking event venues across Nepal. Users can search, filter, and book venues for weddings, birthday parties, corporate events, and cultural celebrations — with a built-in **recommendation algorithm** that suggests the best venues based on popularity, ratings, and user preferences.

---

## 🌐 About the Project

The Nepali event industry relies heavily on manual venue hunting — this system solves that by bringing venue discovery and booking online. It serves three types of users: **guests** who book venues, **venue owners** who list their spaces, and **admins** who manage the platform.

---

## ✨ Key Features

- User registration, login, and session management
- Browse and search venues by event type, location, capacity, and budget
- Real-time availability checker — booked dates are automatically disabled
- Booking conflict prevention — no double bookings on the same date
- Venue owners can list, manage, and confirm/reject bookings
- Users can rate and review venues after their event
- Admin dashboard to manage users, venues, and bookings
- Fully responsive — mobile-friendly using Bootstrap 5

---

## 🤖 Recommendation Algorithm

The system recommends venues by scoring and ranking each one based on four key factors:

| Factor | How It Works |
|---|---|
| **View Count** | Venues with more views are ranked higher — reflects popularity |
| **Average Rating** | Higher rated venues score more — reflects quality |
| **Location Match** | Venues in or near the user's preferred district are ranked higher |
| **Name Match** | Venues whose name matches the user's search keyword get a score boost |

Venues are sorted by their combined score and displayed as **"Recommended for You"** — automatically surfacing the most popular, highly rated, and relevant venues first without the user needing to manually filter through all listings.

---

## 🛠️ Tech Stack

| Technology | Usage |
|---|---|
| PHP | Backend logic, routing, session management |
| MySQL | Database — users, venues, bookings, reviews |
| HTML5 & CSS3 | Structure and custom styling |
| JavaScript | Dynamic calendar, form validation, price updates |
| Bootstrap 5 | Responsive layout and UI components |

---

## ▶️ How to Run Locally

1. Install **XAMPP** or **WAMP**
2. Clone the repo into `htdocs` (XAMPP) or `www` (WAMP)
3. Open `phpMyAdmin` → create database `event_management` → import the `.sql` file
4. Update `config/db.php` with your local credentials
5. Visit `http://localhost/Event-Management-System`

---

## 👤 Author

**Samar Shrestha**
- LinkedIn: [linkedin.com/in/your-profile](https://linkedin.com/in/your-profile)
- GitHub: [github.com/Samar-Shrestha](https://github.com/Samar-Shrestha)
- Email: shresthasamar76@gmail.com

---

> 💡 *Built to digitize venue booking for the Nepali event industry — making it faster, smarter, and accessible from any device.*
