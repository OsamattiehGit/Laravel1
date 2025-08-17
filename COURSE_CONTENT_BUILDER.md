# Course Content Builder Documentation

## Overview
The Course Content Builder allows administrators to create structured, rich course content with text, images, and videos that will be displayed in an organized dropdown format on the course page.

## Features
- **Sectioned Content**: Organize content into logical sections
- **Multiple Content Types**: Support for text, images, and videos
- **File Uploads**: Direct file uploads for images and videos
- **Responsive Design**: Content adapts to different screen sizes
- **Preview Support**: See existing content when editing

## How to Use

### 1. Access the Admin Panel
Navigate to the admin courses section and either create a new course or edit an existing one.

### 2. Course Content Builder Section
In the form, you'll find the "Detailed Course Content" section with:
- **Add Section** button to create new content sections
- **Section Title** field for each section
- **Content Items** within each section

### 3. Adding Content Items
For each content item, you can choose:
- **Text**: Enter text content directly
- **Image**: Upload image files (PNG, JPG, JPEG, SVG)
- **Video**: Upload video files (MP4, WebM, OGG)

### 4. File Management
- **New Files**: Select files to upload (max 10MB)
- **Existing Files**: Previously uploaded files are preserved
- **File Storage**: Files are stored in `storage/app/public/course-content/`

## Content Structure
```json
{
  "course_content_details": [
    {
      "section": "Introduction to Programming",
      "items": [
        {
          "type": "text",
          "value": "Learn the basics of programming concepts..."
        },
        {
          "type": "image",
          "value": "course-content/intro-diagram.png"
        },
        {
          "type": "video",
          "value": "course-content/welcome-video.mp4"
        }
      ]
    }
  ]
}
```

## Frontend Display
The content is automatically displayed on the course page in:
- **Accordion Format**: Expandable sections for better organization
- **Responsive Layout**: Adapts to mobile and desktop screens
- **Proper Media Handling**: Images and videos are properly sized and controlled

## Technical Details

### File Validation
- **Images**: PNG, JPG, JPEG, SVG (max 10MB)
- **Videos**: MP4, WebM, OGG (max 10MB)
- **Text**: No length limit

### Storage
- Files are stored in the `public` disk
- Accessible via `/storage/course-content/` URL
- Automatic cleanup when courses are deleted

### Database Schema
The `courses` table includes a `course_content_details` JSON column that stores the structured content.

## Best Practices

1. **Section Organization**: Use clear, descriptive section titles
2. **Content Mix**: Combine text, images, and videos for engaging content
3. **File Optimization**: Compress images and videos for faster loading
4. **Descriptive Names**: Use meaningful file names for better organization

## Troubleshooting

### Common Issues
- **File Upload Fails**: Check file size and format
- **Images Not Displaying**: Verify file paths and permissions
- **Videos Not Playing**: Ensure browser supports the video format

### File Permissions
Make sure the `storage/app/public` directory is properly linked:
```bash
php artisan storage:link
```

## Future Enhancements
- Support for more file types
- Rich text editor for text content
- Drag and drop file uploads
- Content templates and presets
