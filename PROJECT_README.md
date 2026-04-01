# Bookmark Manager - Laravel Livewire Application

A full-featured bookmark manager application built with Laravel 12, Livewire 3, and Tailwind CSS.

## Features

✅ **User Authentication**

-   Register and login functionality
-   Protected routes with middleware

✅ **Bookmark Management**

-   Add, edit, and delete bookmarks
-   Pin important bookmarks
-   Archive bookmarks
-   Track view counts and last visited dates
-   Auto-fetch favicons from URLs
-   Copy URLs to clipboard

✅ **Tagging System**

-   Create and manage tags
-   Multi-tag filtering
-   Tag-based organization

✅ **Search & Filtering**

-   Real-time search by bookmark title
-   Filter by multiple tags simultaneously
-   Sort by: Recently Added, Recently Visited, or Most Visited
-   Show/hide archived bookmarks

✅ **User Interface**

-   Dark/Light mode toggle
-   Responsive design (mobile, tablet, desktop)
-   View Transition API for smooth filtering animations
-   Modern, clean interface with Tailwind CSS

## Tech Stack

-   **Backend**: Laravel 12
-   **Frontend Framework**: Livewire 3
-   **Styling**: Tailwind CSS
-   **JavaScript**: Alpine.js
-   **Database**: SQLite (default)

## Installation

1. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

2. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. **Database Setup**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

4. **Build Assets**

    ```bash
    npm run build
    # or for development with hot reload:
    npm run dev
    ```

5. **Start Development Server**

    ```bash
    php artisan serve
    ```

6. **Visit the Application**
   Open [http://localhost:8000](http://localhost:8000) in your browser

## Test Account

After seeding, you can log in with:

-   **Email**: test@example.com
-   **Password**: password

## Project Structure

```
app/
├── Livewire/
│   ├── Auth/
│   │   ├── Login.php           # Login component
│   │   └── Register.php        # Registration component
│   ├── Components/
│   │   ├── BookmarkCard.php    # Individual bookmark display
│   │   └── BookmarkForm.php    # Add/Edit bookmark form
│   └── Dashboard.php           # Main dashboard component
├── Models/
│   ├── Bookmark.php            # Bookmark model
│   ├── Tag.php                 # Tag model
│   └── User.php                # User model
database/
├── migrations/
│   ├── create_bookmarks_table.php
│   ├── create_tags_table.php
│   └── create_bookmark_tag_table.php
resources/
├── css/
│   └── app.css                 # Tailwind CSS
├── js/
│   └── app.js                  # Alpine.js & View Transitions
└── views/
    ├── layouts/
    │   ├── app.blade.php       # Main app layout
    │   └── guest.blade.php     # Guest layout (login/register)
    └── livewire/
        ├── auth/               # Authentication views
        ├── components/         # Component views
        └── dashboard.blade.php # Main dashboard view
```

## Key Features Implementation

### Dark Mode

-   Uses Alpine.js to toggle between light and dark themes
-   Preferences saved to localStorage
-   Tailwind CSS dark mode classes

### View Transitions

-   Smooth animations when filtering bookmarks
-   Uses the View Transition API
-   Gracefully degrades on unsupported browsers

### Livewire Components

-   Real-time search with debouncing
-   Dynamic filtering without page reloads
-   Instant tag selection
-   Modal forms for adding/editing bookmarks

## Database Schema

### Bookmarks Table

-   id, user_id, title, description, url, favicon
-   view_count, last_visited_at
-   is_pinned, is_archived
-   timestamps

### Tags Table

-   id, name, color
-   timestamps

### Bookmark_Tag Pivot Table

-   id, bookmark_id, tag_id
-   timestamps

## Development

To run in development mode with hot reload:

```bash
npm run dev
```

In another terminal:

```bash
php artisan serve
```

## Building for Production

```bash
npm run build
php artisan optimize
```

## License

This project is created as a solution to the Frontend Mentor Bookmark Manager App challenge.

## Credits

Built with ❤️ using Laravel, Livewire, and Tailwind CSS.

---

**Happy Bookmarking! 📚**
