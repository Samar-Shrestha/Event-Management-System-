/* home search css part */
/* Search css part */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Georgia', 'Times New Roman', serif;
    overflow-x: hidden;
}


/* Hero Section */
.hero-section {
    position: relative;
    min-height: 5vh;        /* ← Height (full screen height) */
    width: 80vw;              /* ← Width (currently half screen) */
    margin: 0 auto;           /* ← Centers it */
    background: linear-gradient(135deg, #8B4545 0%, #6B2D2D 50%, #4A1F1F 100%);  /* ← Brown color */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;       /* ← Space inside the container */
    overflow: hidden;
}

/* Elegant Background Pattern Overlay */
.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 15% 30%, rgba(255, 215, 0, 0.08) 0%, transparent 40%),
        radial-gradient(circle at 85% 70%, rgba(255, 255, 255, 0.06) 0%, transparent 50%),
        radial-gradient(circle at 50% 50%, rgba(184, 212, 70, 0.04) 0%, transparent 60%);
    pointer-events: none;
    animation: shimmer 20s ease-in-out infinite;
}

/* Decorative elements for event feel */
.hero-section::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        linear-gradient(45deg, transparent 48%, rgba(255, 255, 255, 0.03) 49%, rgba(255, 255, 255, 0.03) 51%, transparent 52%),
        linear-gradient(-45deg, transparent 48%, rgba(255, 255, 255, 0.03) 49%, rgba(255, 255, 255, 0.03) 51%, transparent 52%);
    background-size: 80px 80px;
    opacity: 0.3;
    pointer-events: none;
}

@keyframes shimmer {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

/* Hero Content */
.hero-content {
    text-align: center;
    max-width: 1000px;
    width: 100%;
    z-index: 5;
    animation: fadeInUp 1s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.website-name {
    color: #ffffff;
    font-size: 20px;
    font-weight: 300;
    letter-spacing: 4px;
    margin-bottom: 20px;
    opacity: 0.95;
    text-transform: uppercase;
    font-family: 'Arial', sans-serif;
}

.hero-title {
    color: #ffffff;
    font-size: 64px;
    font-weight: 400;
    line-height: 1.3;
    margin-bottom: 50px;
    text-transform: capitalize;
    letter-spacing: 2px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.hero-title .highlight {
    color: #B8D446;
    font-weight: 700;
    font-style: italic;
    position: relative;
    display: inline-block;
}

.hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #B8D446, transparent);
    opacity: 0.5;
}

/* Search Form */
.search-form {
    margin-bottom: 50px;
    animation: fadeInUp 1s ease-out 0.2s backwards;
}

.search-container {
    display: flex;
    background: rgba(255, 255, 255, 0.98);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 60px rgba(0, 0, 0, 0.4), 0 5px 20px rgba(0, 0, 0, 0.2);
    max-width: 900px;
    margin: 0 auto;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.search-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 70px rgba(0, 0, 0, 0.5), 0 8px 25px rgba(0, 0, 0, 0.3);
}

.search-input-wrapper {
    flex: 1;
    position: relative;
}

.search-select,
.search-input {
    width: 100%;
    padding: 20px 24px;
    border: none;
    font-size: 16px;
    outline: none;
    background: transparent;
    color: #333;
    appearance: none;
    cursor: pointer;
    font-family: 'Arial', sans-serif;
    transition: background 0.2s ease;
}

.search-select:hover,
.search-input:hover {
    background: rgba(184, 212, 70, 0.05);
}

.search-select {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="9" viewBox="0 0 14 9"><path fill="%23C41E3A" d="M1.41 0L7 5.59 12.59 0 14 1.41l-7 7-7-7z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 20px center;
    padding-right: 50px;
    font-weight: 500;
}

.search-input::placeholder {
    color: #999;
    font-style: italic;
}

.location-wrapper {
    border-left: 2px solid rgba(196, 30, 58, 0.1);
    border-right: 2px solid rgba(196, 30, 58, 0.1);
}

.search-button {
    background: linear-gradient(135deg, #C41E3A 0%, #A01828 100%);
    color: white;
    border: none;
    padding: 0 40px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    position: relative;
    overflow: hidden;
}

.search-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.search-button:hover::before {
    left: 100%;
}

.search-button:hover {
    background: linear-gradient(135deg, #A01828 0%, #8B1520 100%);
    box-shadow: 0 5px 20px rgba(196, 30, 58, 0.4);
}

.search-button svg {
    width: 26px;
    height: 26px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

/* Location Suggestions */
.location-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid rgba(196, 30, 58, 0.2);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 250px;
    overflow-y: auto;
    display: none;
    z-index: 100;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.location-suggestions.active {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.location-suggestion {
    padding: 14px 24px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f5f5f5;
    font-size: 15px;
}

.location-suggestion:hover {
    background: linear-gradient(90deg, rgba(184, 212, 70, 0.1), rgba(196, 30, 58, 0.05));
    padding-left: 28px;
    color: #C41E3A;
}

.location-suggestion:last-child {
    border-bottom: none;
}

/* Popular Searches */
.popular-searches {
    margin-bottom: 40px;
    animation: fadeInUp 1s ease-out 0.4s backwards;
}

.popular-label {
    color: white;
    font-size: 16px;
    font-weight: 300;
    letter-spacing: 2px;
    margin-bottom: 20px;
    opacity: 0.95;
    text-transform: uppercase;
    font-family: 'Arial', sans-serif;
}

.popular-label::before,
.popular-label::after {
    content: '~';
    margin: 0 15px;
    opacity: 0.6;
}

.popular-tags {
    display: flex;
    gap: 18px;
    justify-content: center;
    flex-wrap: wrap;
}

.tag {
    background: rgba(255, 255, 255, 0.12);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.25);
    padding: 12px 28px;
    border-radius: 30px;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(15px);
    font-weight: 400;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.tag::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
    transition: left 0.5s ease;
}

.tag:hover::before {
    left: 100%;
}

.tag:hover {
    background: rgba(255, 255, 255, 0.25);
    border-color: rgba(255, 255, 255, 0.6);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    color: #B8D446;
}

/* Contact Button */
.contact-button {
    display: inline-block;
    padding: 18px 60px;
    background: transparent;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.8);
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.4s ease;
    letter-spacing: 2px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
    font-family: 'Arial', sans-serif;
    animation: fadeInUp 1s ease-out 0.6s backwards;
}

.contact-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: white;
    transition: width 0.6s ease, height 0.6s ease, top 0.6s ease, left 0.6s ease;
    transform: translate(-50%, -50%);
    z-index: -1;
}

.contact-button:hover::before {
    width: 400px;
    height: 400px;
}

.contact-button:hover {
    color: #8B4545;
    border-color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-title {
        font-size: 52px;
    }
    
    .search-container {
        max-width: 750px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 40px 20px;
        min-height: 90vh;
    }

    .hero-title {
        font-size: 42px;
        margin-bottom: 40px;
        letter-spacing: 1px;
    }

    .website-name {
        font-size: 16px;
        letter-spacing: 3px;
    }

    .search-container {
        flex-direction: column;
        max-width: 90%;
    }

    .location-wrapper {
        border-left: none;
        border-right: none;
        border-top: 2px solid rgba(196, 30, 58, 0.1);
        border-bottom: 2px solid rgba(196, 30, 58, 0.1);
    }

    .search-button {
        padding: 20px;
    }

    .popular-tags {
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }

    .tag {
        width: 90%;
        max-width: 350px;
        text-align: center;
    }

    .contact-button {
        padding: 16px 50px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 32px;
        line-height: 1.4;
    }

    .website-name {
        font-size: 14px;
        letter-spacing: 2px;
    }

    .search-select,
    .search-input {
        font-size: 15px;
        padding: 18px 20px;
    }

    .search-button {
        padding: 18px;
    }

    .popular-label {
        font-size: 14px;
    }

    .tag {
        font-size: 14px;
        padding: 10px 24px;
    }

    .contact-button {
        padding: 14px 40px;
        font-size: 13px;
        letter-spacing: 1.5px;
    }
}





<!-- Home header search options  -->
 <!-- main search options -->



					<!-- Hero Section -->
    <section class="hero-section">
        <!-- Logo
        <div class="logo-container">
            <img src="images\LOGO.jpg" alt="ClassicEvents Logo" class="logo">
        </div> -->

        <!-- Hero Content -->
        <div class="hero-content">
            <p class="website-name">ClassicEvents.com</p>
            <h1 class="hero-title">
                We are <span class="highlight">HERE</span><br>
                for your every needs
            </h1>

            <!-- Search Form -->
            <form class="search-form" id="searchForm" action="search.php" method="GET">
                <div class="search-container">
                    <!-- Service Dropdown -->
                    <div class="search-input-wrapper">
                        <select name="service" id="serviceSelect" class="search-select" required>
                            <option value="">Select Service</option>
                            <option value="venue">Venue</option>
                            <option value="decorations">Decorations</option>
                            <option value="catering">Catering</option>
                            <option value="invitation">Invitation Cards</option>
                            <!-- <option value="photography">Photography & Videography</option>
                            <option value="makeup">Makeup</option>
                            <option value="clothing">Clothing</option>
                            <option value="music">Music (Baja)</option>
                            <option value="mehendi">Mehendi Artist</option> -->
                        </select>
                    </div>

                    <!-- Location Input -->
                    <div class="search-input-wrapper location-wrapper">
                        <input 
                            type="text" 
                            name="location" 
                            id="locationInput" 
                            class="search-input" 
                            placeholder="Select location"
                            autocomplete="off"
                            required
                        >
                        <!-- Location Suggestions Dropdown -->
                        <div class="location-suggestions" id="locationSuggestions"></div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Popular Searches -->
            <div class="popular-searches">
                <p class="popular-label">~ Popular Searches ~</p>
                <div class="popular-tags">
                    <button class="tag" data-service="photography" data-location="Kathmandu">
                        Wedding Photographers in Kathmandu
                    </button>
                    <button class="tag" data-service="makeup" data-location="Pokhara">
                        Bridal Makeup in Pokhara
                    </button>
                    <button class="tag" data-service="music" data-location="">
                        Music artist for Event
                    </button>
                </div>
            </div>

            <!-- Contact Button -->
            <a href="#contact" class="contact-button">CONTACT US</a>
        </div>

        <!-- Enquiry Tab
        <div class="enquiry-tab">ENQUIRY</div> -->
    </section>