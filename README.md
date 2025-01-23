<h2>Introduction</h2>
<p>This is a Laravel-based project designed with two roles: <code>Admin</code> and <code>User</code>. Below are the instructions to set up, configure, and run the project.</p>

<h2>Requirements</h2>
<ul>
    <li>PHP >= 7.3</li>
    <li>Composer</li>
    <li>MySQL or another supported database</li>
</ul>

<h2>Installation</h2>
<ol>
    <li>Clone this repository:
        <pre><code>git clone &lt;repository-url&gt;</code></pre>
    </li>
    <li>Navigate to the project directory:
        <pre><code>cd &lt;project-folder&gt;</code></pre>
    </li>
    <li>Install dependencies:
        <pre><code>composer install</code></pre>
    </li>
    <li>Copy the <code>.env</code> file:
        <pre><code>cp .env.example .env</code></pre>
    </li>
    <li>Generate the application key:
        <pre><code>php artisan key:generate</code></pre>
    </li>
    <li>Run the migrations and seed the database:
        <pre><code>php artisan migrate --seed</code></pre>
    </li>
    <li>Generate the JWT secret:
        <pre><code>php artisan jwt:secret</code></pre>
    </li>
    <li>Optimize the autoloader:
        <pre><code>composer dump-autoload</code></pre>
    </li>
</ol>

<h2>Roles and Credentials</h2>
<p>The project supports two roles: <code>Admin</code> and <code>User</code>. Use the following credentials to log in as an admin:</p>
<ul>
    <li><strong>Email:</strong> superadmin@gmail.com</li>
    <li><strong>Password:</strong> 12341234</li>
</ul>

<h2>Running the Application</h2>
<ol>
    <li>Start the queue worker and scheduler:
        <pre><code>php artisan schedule:work</code></pre>
    </li>
    <li>Configure your SMTP settings in the <code>.env</code> file to enable email functionality:
        <pre><code>
MAIL_MAILER=smtp
MAIL_HOST=<your-smtp-host>
MAIL_PORT=<your-smtp-port>
MAIL_USERNAME=<your-smtp-username>
MAIL_PASSWORD=<your-smtp-password>
MAIL_ENCRYPTION=<tls/ssl>
MAIL_FROM_ADDRESS=<your-email>
MAIL_FROM_NAME="<your-name>"
<h2>Caching</h2>
<p>This project uses the database for caching. Ensure your database connection is properly configured in the <code>.env</code> file:</p>
<pre><code>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<your-database>
DB_USERNAME=<your-username>
DB_PASSWORD=<your-password>
<h2>Running Tests</h2>
<p>To run the unit tests, execute the following command:</p>
<pre><code>php artisan test</code></pre>

<h2>License</h2>
<p>This project is licensed under the <a href="LICENSE">MIT License</a>.</p>

<hr>

<p>Feel free to contribute and create pull requests to improve the project! For any issues or inquiries, please contact the repository maintainer.</p>
