# Notification System

## Overview

Sistem notifikasi yang menampilkan notifikasi dari database ke layout admin dan super admin. Sistem ini mencakup dropdown notifikasi real-time dan profile dropdown dengan logout functionality.

## Features

### 🔔 Real-time Notifications

-   **Dynamic Loading**: Notifikasi dimuat dari database secara real-time
-   **Auto Refresh**: Notifikasi di-refresh setiap 30 detik
-   **Unread Counter**: Badge merah menampilkan jumlah notifikasi belum dibaca
-   **Interactive Actions**: Mark as read, delete, mark all as read

### 👤 Profile Dropdown

-   **User Info**: Menampilkan nama, email, dan role user
-   **Menu Options**: Profile settings, account settings, notification settings, help & support
-   **Logout Button**: Tombol logout yang terintegrasi dengan form logout

### 🎨 UI/UX Features

-   **Alpine.js Integration**: Menggunakan Alpine.js untuk interaktivitas
-   **Smooth Animations**: Transisi smooth untuk dropdown
-   **Dark Mode Support**: Mendukung tema gelap
-   **Responsive Design**: Responsif untuk berbagai ukuran layar

## Database Schema

### notifications Table

```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## File Structure

```
app/
├── Models/Notification.php                    # Model untuk notifikasi
└── Http/Controllers/Admin/NotificationController.php  # Controller untuk notifikasi

resources/views/
└── components/admin-layout.blade.php         # Layout admin dengan notifikasi

routes/
└── admin.php                                 # Routes untuk notifikasi
```

## API Endpoints

### Get Notifications

```http
GET /admin/notifications
Response: {
    "notifications": [...],
    "unread_count": 5
}
```

### Mark as Read

```http
PUT /admin/notifications/{id}/read
Response: {
    "success": true
}
```

### Mark All as Read

```http
PUT /admin/notifications/mark-all-read
Response: {
    "success": true
}
```

### Delete Notification

```http
DELETE /admin/notifications/{id}
Response: {
    "success": true
}
```

### Get Unread Count

```http
GET /admin/notifications/unread-count
Response: {
    "count": 5
}
```

## Notification Types

### Type Icons

-   **success**: `fa-check-circle` (green)
-   **error**: `fa-exclamation-circle` (red)
-   **warning**: `fa-exclamation-triangle` (yellow)
-   **info**: `fa-info-circle` (blue)
-   **default**: `fa-bell` (gray)

### Type Classes

-   **success**: `bg-green-100 text-green-800`
-   **error**: `bg-red-100 text-red-800`
-   **warning**: `bg-yellow-100 text-yellow-800`
-   **info**: `bg-blue-100 text-blue-800`

## JavaScript Functions

### Core Functions

-   `fetchNotifications()` - Fetch notifications from API
-   `markAsRead(notificationId)` - Mark notification as read
-   `markAllAsRead()` - Mark all notifications as read
-   `deleteNotification(notificationId)` - Delete notification
-   `getNotificationIcon(type)` - Get icon class for notification type
-   `formatDate(dateString)` - Format date for display

### Auto Refresh

```javascript
setInterval(fetchNotifications, 30000); // Refresh every 30 seconds
```

## Usage Examples

### Creating Notifications

```php
// Create notification for user
Notification::create([
    'user_id' => $userId,
    'title' => 'Welcome!',
    'message' => 'Welcome to HagaPlus system.',
    'type' => 'info'
]);

// Create success notification
Notification::create([
    'user_id' => $userId,
    'title' => 'Payment Successful',
    'message' => 'Your payment has been processed successfully.',
    'type' => 'success'
]);
```

### Querying Notifications

```php
// Get unread notifications for user
$unreadNotifications = Notification::where('user_id', $userId)
    ->unread()
    ->get();

// Get notifications by type
$errorNotifications = Notification::where('user_id', $userId)
    ->byType('error')
    ->get();

// Mark notification as read
$notification = Notification::find($id);
$notification->markAsRead();
```

## Frontend Integration

### Alpine.js Data

```javascript
x-data="{
    open: false,
    notifications: [],
    unreadCount: 0
}"
```

### Event Handlers

```javascript
@click="open = !open"           // Toggle dropdown
@click.away="open = false"      // Close on outside click
@click="markAsRead(notification.id)"  // Mark as read
@click.stop="deleteNotification(notification.id)"  // Delete notification
```

## Styling

### CSS Classes

-   **Notification Container**: `relative`, `x-data`
-   **Dropdown**: `absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800`
-   **Notification Item**: `px-4 py-3 border-b border-gray-100`
-   **Unread Highlight**: `bg-blue-50 dark:bg-blue-900/20`
-   **Profile Dropdown**: `w-64 bg-white dark:bg-gray-800`

### Responsive Design

-   **Mobile**: Hidden on small screens (`hidden sm:inline-flex`)
-   **Desktop**: Full functionality on larger screens
-   **Dropdown**: Positioned absolutely with proper z-index

## Security

### CSRF Protection

```javascript
'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
```

### User Authorization

-   Notifications are filtered by `user_id`
-   Users can only access their own notifications
-   Proper middleware protection on routes

## Performance

### Optimizations

-   **Auto Refresh**: 30-second interval for real-time updates
-   **Pagination**: Limited to 10 notifications in dropdown
-   **Efficient Queries**: Optimized database queries
-   **Caching**: Consider implementing Redis cache for high-traffic scenarios

### Database Indexes

```sql
-- Recommended indexes
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
CREATE INDEX idx_notifications_created_at ON notifications(created_at);
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
```

## Testing

### Test Coverage

-   ✅ Model relationships
-   ✅ Controller methods
-   ✅ Route accessibility
-   ✅ Database queries
-   ✅ Frontend functionality

### Manual Testing

1. Login as admin/employee
2. Check notification bell icon shows unread count
3. Click bell to open notifications dropdown
4. Test mark as read functionality
5. Test delete notification
6. Test mark all as read
7. Click profile icon to see dropdown
8. Test logout functionality

## Future Enhancements

-   [ ] Push notifications for real-time updates
-   [ ] Email notifications integration
-   [ ] Notification preferences per user
-   [ ] Bulk notification actions
-   [ ] Notification categories/filtering
-   [ ] Rich text notifications with HTML
-   [ ] Notification templates
-   [ ] Analytics and reporting
-   [ ] Mobile app notifications
-   [ ] WebSocket integration for instant updates
