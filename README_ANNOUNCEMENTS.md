# Course Announcements System

## Overview
The course announcements system allows administrators to create and manage announcements that are automatically sent to all users enrolled in specific courses.

## How to Add Announcements (Admin Only)

### 1. Access the Admin Panel
- Log in with an admin account
- Click the settings icon (⚙️) in the top-right corner of the navbar
- This will take you to the Admin Dashboard

### 2. Navigate to Announcements
- From the Admin Dashboard, click on "Announcements" in the navigation
- Or directly access: `/admin/announcements`

### 3. Create a New Announcement
- Click the "+ Add Announcement" button
- Fill in the required fields:
  - **Course**: Select which course this announcement is for
  - **Title**: A brief, descriptive title
  - **Content**: The full announcement message
  - **Priority**: Choose from Low, Normal, High, or Urgent
  - **Expires At**: Optional expiration date (leave blank for permanent)
  - **Pin this announcement**: Check to keep it at the top

### 4. Submit the Announcement
- Click "Create Announcement"
- The announcement will be immediately sent to all enrolled users
- Users will see it in their chat widget under the "Announcements" tab

## Announcement Features

### Priority Levels
- **Low**: General information (green badge)
- **Normal**: Standard updates (blue badge)
- **High**: Important notices (yellow badge)
- **Urgent**: Critical information (red badge)

### Pinning
- Pinned announcements appear at the top of the list
- Useful for important, ongoing information

### Expiration
- Set an expiration date for time-sensitive announcements
- Expired announcements are automatically hidden from users

## User Experience

### Real-time Delivery
- Announcements are delivered instantly via Pusher
- Users see them immediately in their chat widget
- No need to refresh the page

### Chat Widget Integration
- Users can view announcements in the floating chat widget
- Click the chat button (bottom-left) → "Announcements" tab
- Announcements are organized by course

### Mobile Friendly
- Chat widget works on all devices
- Responsive design for mobile users

## Admin Management

### View All Announcements
- See all announcements in a table format
- Filter by course, priority, and status
- View creation dates and admin who posted

### Edit Announcements
- Click "Edit" to modify existing announcements
- Update content, priority, or expiration
- Changes are reflected immediately

### Delete Announcements
- Remove outdated or incorrect announcements
- Confirmation dialog prevents accidental deletion

### Toggle Pin Status
- Pin/unpin announcements as needed
- Useful for managing multiple important announcements

## Technical Details

### Broadcasting
- Uses Laravel Echo and Pusher for real-time delivery
- Announcements are sent to course-specific channels
- Only enrolled users receive course announcements

### Database
- Stored in `course_announcements` table
- Linked to courses and admin users
- Supports soft deletes for data integrity

### Security
- Admin-only access via middleware
- CSRF protection on all forms
- Input validation and sanitization

## Troubleshooting

### Announcement Not Showing
- Check if user is enrolled in the course
- Verify announcement hasn't expired
- Ensure Pusher is properly configured

### Admin Access Issues
- Verify user has 'admin' role in database
- Check admin middleware is working
- Ensure routes are properly protected

### Real-time Issues
- Check Pusher configuration in `.env`
- Verify Laravel Echo is loaded
- Check browser console for JavaScript errors

## Best Practices

### Content Guidelines
- Keep titles concise and descriptive
- Use clear, professional language
- Include relevant course context

### Frequency
- Don't spam users with too many announcements
- Use priority levels appropriately
- Pin only the most important announcements

### Timing
- Consider when users are most active
- Use expiration dates for time-sensitive info
- Archive old announcements regularly
