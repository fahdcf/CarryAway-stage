# CarryWay User Dashboard

## 🎯 Features Implemented

### ✅ Core Functionality
- **Post Delivery Announcement** - Users can create announcements to send packages
- **Post Travel Announcement** - Users can announce trips with available cargo space
- **View Own Announcements** - Users can see and manage their own announcements
- **View Other Users' Announcements** - Users can browse announcements from other users
- **Static "Demander cette trajectoire" Button** - UI element on every announcement card

### 🔧 Technical Implementation

#### Backend (Laravel)
- **Controller**: `UserDashboardController` with full CRUD operations
- **Models**: Proper relationships between User, TravelerAnnouncement, SenderAnnouncement, and their details
- **Routes**: RESTful routes with proper middleware protection
- **Middleware**: `ApprovedUserMiddleware` ensures only approved users can access dashboard
- **Validation**: Comprehensive form validation for all announcement fields

#### Frontend (Blade + Tailwind CSS)
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Modern UI**: Clean, professional design following CarryWay's design system
- **Component-based**: Reusable announcement card components
- **Navigation**: Tab-based navigation between different views

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── UserDashboardController.php
│   └── Middleware/
│       └── ApprovedUserMiddleware.php
├── Models/
│   ├── TravelerAnnouncement.php
│   ├── TravelerAnnouncementDetail.php
│   ├── SenderAnnouncement.php
│   └── SenderAnnouncementDetail.php

resources/views/dashboard/
├── index.blade.php (Main dashboard)
├── my-announcements.blade.php (User's own announcements)
├── all-announcements.blade.php (Other users' announcements)
├── create-traveler-announcement.blade.php (Create travel announcement)
├── create-sender-announcement.blade.php (Create delivery announcement)
└── components/
    ├── traveler-announcement-card.blade.php
    └── sender-announcement-card.blade.php
```

## 🚀 Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/dashboard` | Main dashboard overview |
| GET | `/dashboard/my-announcements` | User's own announcements |
| GET | `/dashboard/all-announcements` | All other users' announcements |
| GET | `/dashboard/create-traveler-announcement` | Create travel announcement form |
| POST | `/dashboard/store-traveler-announcement` | Store travel announcement |
| GET | `/dashboard/create-sender-announcement` | Create delivery announcement form |
| POST | `/dashboard/store-sender-announcement` | Store delivery announcement |
| POST | `/dashboard/toggle-announcement/{type}/{id}` | Toggle announcement status |
| DELETE | `/dashboard/delete-announcement/{type}/{id}` | Delete announcement |

## 🎨 UI Components

### Announcement Cards
- **Traveler Cards**: Blue theme with location icons
- **Sender Cards**: Green theme with package icons
- **Status Indicators**: Active/Inactive badges
- **Action Buttons**: Different actions for own vs other users' announcements

### Forms
- **Traveler Form**: Departure/arrival cities, dates, weight, transport mode, pricing
- **Sender Form**: Pickup/delivery cities, dates, package details, value declaration
- **Validation**: Real-time validation with error messages
- **Responsive**: Mobile-friendly form layouts

## 🔐 Security Features

- **Middleware Protection**: Only approved users can access dashboard
- **Ownership Validation**: Users can only manage their own announcements
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Comprehensive server-side validation
- **XSS Protection**: Proper escaping in Blade templates

## 📊 Database Schema

### Traveler Announcements
- Basic info: departure/arrival cities, date, available weight
- Details: transport mode, estimated arrival, price per kg, notes
- Status: active/inactive, approval status

### Sender Announcements
- Basic info: pickup/delivery cities, preferred date, package weight
- Details: item type, dimensions, declared value, additional info
- Status: active/inactive, approval status

## 🧪 Testing

### Test Data
Run the seeder to create test data:
```bash
php artisan db:seed --class=CarryWaySeeder
```

### Test Users
- **Admin**: `admin@carryway.ma` / `admin123`
- **User 1**: `ahmed@example.com` / `password`
- **User 2**: `fatima@example.com` / `password`
- **User 3**: `mohammed@example.com` / `password`

## 🎯 Key Features

### 1. Create Travel Announcements
- Users can announce their travel plans
- Specify departure/arrival cities and dates
- Set available cargo capacity
- Add transport mode and pricing
- Include detailed descriptions and notes

### 2. Create Delivery Announcements
- Users can request package delivery
- Specify pickup/delivery locations
- Set package weight and dimensions
- Declare package value
- Add detailed package descriptions

### 3. Manage Own Announcements
- View all personal announcements
- Toggle active/inactive status
- Delete announcements
- See creation dates and status

### 4. Browse Other Announcements
- View all active announcements from other users
- See detailed information about each announcement
- "Demander cette trajectoire" button (static UI for now)
- Filter by announcement type

### 5. Responsive Design
- Mobile-first approach
- Clean, modern interface
- Consistent with CarryWay design system
- Accessible and user-friendly

## 🔄 Next Steps

The dashboard is fully functional with all requested features. Future enhancements could include:

1. **Real Request System**: Implement actual request functionality behind "Demander cette trajectoire"
2. **Search & Filter**: Add search and filtering capabilities
3. **Notifications**: Real-time notifications for new announcements
4. **Chat System**: Direct messaging between users
5. **Payment Integration**: Handle payments for delivery services
6. **Rating System**: User reviews and ratings
7. **Advanced Matching**: AI-powered matching algorithms

## 🚀 Usage

1. **Login**: Use any of the test user accounts
2. **Navigate**: Use the tab navigation to switch between views
3. **Create**: Use the "Create Announcement" buttons to add new announcements
4. **Manage**: Use the action buttons on your own announcements
5. **Browse**: View other users' announcements in the "All Announcements" section

The dashboard is production-ready and follows Laravel best practices with proper security, validation, and user experience considerations. 