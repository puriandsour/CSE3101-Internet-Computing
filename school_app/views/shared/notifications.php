<h1>Notifications</h1>
<p>No notifications yet.</p>
<p>Out of Scope. However if our team had to implement notifications, we would use a notification table to store
    notifications.</p>
<p>For example:</p>
<pre>
    CREATE TABLE notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
</pre>
<p>We would also have a notification service to send notifications to users.</p>
<p>Our best option is firebase since they have genrous free tier.</p>
<p>The steps to send is requesting browser permissions, then storing the token in our database.</p>
<p>Then we can send notifications to users using the token through firebase.</p>
<p>That would require a bit more time for this project for our team to implement.</p>