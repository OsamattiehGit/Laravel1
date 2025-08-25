export function requestNotificationPermission() {
    if (typeof window === 'undefined' || !('Notification' in window)) {
      return;
    }
    if (Notification.permission === 'default') {
      Notification.requestPermission();
    }
  }
  
  export function showNotification(title, body) {
    if (typeof window === 'undefined' || !('Notification' in window)) {
      return;
    }
    if (Notification.permission === 'granted') {
      new Notification(title, { body });
    } else if (Notification.permission !== 'denied') {
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          new Notification(title, { body });
        }
      });
    }
  }
  
  export function testNotification() {
    if (typeof window === 'undefined' || !('Notification' in window)) {
      console.log('Notifications not supported in this browser');
      return false;
    }
    
    if (Notification.permission === 'granted') {
      new Notification('Test Notification', { 
        body: 'This is a test notification from EzySkills!',
        icon: '/images/notification-icon.svg',
        badge: '/images/notification-badge.svg'
      });
      return true;
    } else if (Notification.permission === 'default') {
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          testNotification();
        }
      });
      return false;
    } else {
      console.log('Notification permission denied');
      return false;
    }
  }
  
  // Expose globally for inline scripts
  window.requestNotificationPermission = requestNotificationPermission;
  window.showDesktopNotification = showNotification;
  window.testNotification = testNotification;